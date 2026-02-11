<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database
                            {--path= : Custom output directory path}
                            {--filename= : Custom filename (without extension)}
                            {--compress : Compress output with gzip}
                            {--max-files=10 : Maximum number of backup files to keep (0 = unlimited)}';

    protected $description = 'Create a database backup in phpMyAdmin-compatible SQL format';

    /**
     * Column types that should NOT be quoted in INSERT values.
     */
    protected array $numericTypes = [
        'int', 'bigint', 'mediumint', 'smallint', 'tinyint',
        'decimal', 'float', 'double', 'real',
    ];

    public function handle(): int
    {
        $startTime = microtime(true);

        $database = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $this->info("Starting phpMyAdmin-style backup for database: {$database}");

        // Determine output path
        $backupDir = $this->option('path') ?: storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Generate filename
        $filename = $this->option('filename')
            ?: $database . '_' . date('Y-m-d_His');
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $filename . '.sql';

        $this->info("Output: {$filePath}");

        try {
            $handle = fopen($filePath, 'w');
            if (!$handle) {
                $this->error("Cannot open file for writing: {$filePath}");
                return Command::FAILURE;
            }

            // Get server info
            $serverVersion = DB::selectOne("SELECT VERSION() as v")->v;
            $phpVersion = PHP_VERSION;
            $generationTime = now()->setTimezone('UTC')->format('M d, Y \a\t h:i A');

            // ========== HEADER ==========
            $this->writeHeader($handle, $host, $port, $serverVersion, $phpVersion, $generationTime, $database);

            // ========== TABLES ==========
            $tables = $this->getTables();
            $tableCount = count($tables);
            $this->info("Found {$tableCount} tables");

            $bar = $this->output->createProgressBar($tableCount);
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% -- %message%');
            $bar->start();

            $tableColumns = [];
            $tableIndexes = [];
            $tableAutoIncrements = [];
            $tableConstraints = [];

            foreach ($tables as $i => $table) {
                $bar->setMessage("Exporting: {$table}");

                // Write table separator
                $this->writeLine($handle, '');
                $this->writeLine($handle, '-- --------------------------------------------------------');

                // Write CREATE TABLE
                $this->writeTableStructure($handle, $table);

                // Collect index, auto_increment, constraint info
                $tableColumns[$table] = $this->getColumnInfo($table);
                $tableIndexes[$table] = $this->getIndexes($table);
                $autoInc = $this->getAutoIncrement($table);
                if ($autoInc) {
                    $tableAutoIncrements[$table] = $autoInc;
                }
                $constraints = $this->getConstraints($table);
                if (!empty($constraints)) {
                    $tableConstraints[$table] = $constraints;
                }

                // Write INSERT data
                $this->writeTableData($handle, $table, $tableColumns[$table]);

                $bar->advance();
            }

            $bar->setMessage('Tables exported!');
            $bar->finish();
            $this->newLine(2);

            // ========== INDEXES ==========
            $this->info('Writing indexes...');
            $this->writeIndexes($handle, $tableIndexes);

            // ========== AUTO_INCREMENT ==========
            $this->info('Writing auto_increment values...');
            $this->writeAutoIncrements($handle, $tableAutoIncrements);

            // ========== CONSTRAINTS ==========
            if (!empty($tableConstraints)) {
                $this->info('Writing constraints...');
                $this->writeConstraints($handle, $tableConstraints);
            }

            // ========== FOOTER ==========
            $this->writeFooter($handle);

            fclose($handle);

            // Compress if requested
            if ($this->option('compress')) {
                $gzPath = $filePath . '.gz';
                $this->info("Compressing to {$gzPath}...");
                $this->compressFile($filePath, $gzPath);
                unlink($filePath);
                $filePath = $gzPath;
            }

            // Cleanup old backups
            $maxFiles = (int) $this->option('max-files');
            if ($maxFiles > 0) {
                $this->cleanupOldBackups($backupDir, $maxFiles);
            }

            $elapsed = round(microtime(true) - $startTime, 2);
            $size = $this->formatBytes(filesize($filePath));

            $this->info("Backup completed successfully!");
            $this->info("File: {$filePath}");
            $this->info("Size: {$size}");
            $this->info("Time: {$elapsed}s");
            $this->info("Tables: {$tableCount}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            // Remove partial file
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->error("Backup failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    // ─── Header ──────────────────────────────────────────────

    protected function writeHeader($handle, string $host, string $port, string $serverVersion, string $phpVersion, string $generationTime, string $database): void
    {
        $this->writeLine($handle, '-- phpMyAdmin SQL Dump');
        $this->writeLine($handle, '-- version 5.2.2');
        $this->writeLine($handle, '-- https://www.phpmyadmin.net/');
        $this->writeLine($handle, '--');
        $this->writeLine($handle, "-- Host: {$host}:{$port}");
        $this->writeLine($handle, "-- Generation Time: {$generationTime}");
        $this->writeLine($handle, "-- Server version: {$serverVersion}");
        $this->writeLine($handle, "-- PHP Version: {$phpVersion}");
        $this->writeLine($handle, '');
        $this->writeLine($handle, 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');
        $this->writeLine($handle, 'START TRANSACTION;');
        $this->writeLine($handle, 'SET time_zone = "+00:00";');
        $this->writeLine($handle, '');
        $this->writeLine($handle, '');
        $this->writeLine($handle, '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;');
        $this->writeLine($handle, '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;');
        $this->writeLine($handle, '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;');
        $this->writeLine($handle, '/*!40101 SET NAMES utf8mb4 */;');
        $this->writeLine($handle, '');
        $this->writeLine($handle, '--');
        $this->writeLine($handle, "-- Database: `{$database}`");
        $this->writeLine($handle, '--');
    }

    // ─── Table structure ─────────────────────────────────────

    protected function writeTableStructure($handle, string $table): void
    {
        $this->writeLine($handle, '');
        $this->writeLine($handle, '--');
        $this->writeLine($handle, "-- Table structure for table `{$table}`");
        $this->writeLine($handle, '--');
        $this->writeLine($handle, '');

        $createResult = DB::selectOne("SHOW CREATE TABLE `{$table}`");
        $createSql = $createResult->{'Create Table'};

        // Remove AUTO_INCREMENT=xxx from CREATE TABLE (phpMyAdmin style)
        $createSql = preg_replace('/ AUTO_INCREMENT=\d+/', '', $createSql);

        $this->writeLine($handle, $createSql . ';');
    }

    // ─── Table data ──────────────────────────────────────────

    protected function writeTableData($handle, string $table, array $columns): void
    {
        $count = DB::table($table)->count();
        if ($count === 0) {
            return;
        }

        $this->writeLine($handle, '');
        $this->writeLine($handle, '--');
        $this->writeLine($handle, "-- Dumping data for table `{$table}`");
        $this->writeLine($handle, '--');
        $this->writeLine($handle, '');

        $columnNames = array_keys($columns);
        $escapedColumns = array_map(fn($c) => "`{$c}`", $columnNames);
        $columnList = implode(', ', $escapedColumns);

        $batchSize = 100;
        $insertPrefix = "INSERT INTO `{$table}` ({$columnList}) VALUES";
        $rowsInCurrentStatement = 0;
        $maxRowsPerStatement = 200; // phpMyAdmin splits long INSERTs

        DB::table($table)->orderBy(array_key_first($columns))->chunk($batchSize, function ($rows) use (
            $handle, $table, $columns, $columnNames, $insertPrefix, &$rowsInCurrentStatement, $maxRowsPerStatement
        ) {
            foreach ($rows as $row) {
                $rowArray = (array) $row;

                // Start new INSERT statement if needed
                if ($rowsInCurrentStatement === 0) {
                    fwrite($handle, $insertPrefix . "\n");
                }

                // Build value row
                $values = [];
                foreach ($columnNames as $col) {
                    $value = $rowArray[$col] ?? null;
                    $values[] = $this->formatValue($value, $columns[$col]);
                }

                $valueStr = '(' . implode(', ', $values) . ')';

                $rowsInCurrentStatement++;

                if ($rowsInCurrentStatement >= $maxRowsPerStatement) {
                    // End current statement, next iteration starts new one
                    fwrite($handle, $valueStr . ";\n");
                    $rowsInCurrentStatement = 0;
                } else {
                    // We don't know if there are more rows, write with comma
                    // We'll fix the last row's comma at the end
                    fwrite($handle, $valueStr . ",\n");
                }
            }
        });

        // Fix the trailing comma of the last row → replace with semicolon
        if ($rowsInCurrentStatement > 0) {
            // Seek back and replace the last ",\n" with ";\n"
            $pos = ftell($handle);
            fseek($handle, $pos - 2); // go back over ",\n"
            fwrite($handle, ";\n");
        }
    }

    protected function formatValue($value, string $type): string
    {
        if ($value === null) {
            return 'NULL';
        }

        // Determine base type
        $baseType = strtolower(preg_replace('/\(.*\)/', '', $type));
        $baseType = trim(explode(' ', $baseType)[0]);

        if (in_array($baseType, $this->numericTypes, true)) {
            return (string) $value;
        }

        // String-based: escape and quote
        $escaped = addslashes((string) $value);
        // Handle special characters like phpMyAdmin
        $escaped = str_replace(["\r\n", "\r", "\n", "\t"], ['\\r\\n', '\\r', '\\n', '\\t'], $escaped);

        return "'" . $escaped . "'";
    }

    // ─── Indexes ─────────────────────────────────────────────

    protected function writeIndexes($handle, array $tableIndexes): void
    {
        $this->writeLine($handle, '');
        $this->writeLine($handle, '--');
        $this->writeLine($handle, '-- Indexes for dumped tables');
        $this->writeLine($handle, '--');

        foreach ($tableIndexes as $table => $indexes) {
            if (empty($indexes)) {
                continue;
            }

            $this->writeLine($handle, '');
            $this->writeLine($handle, '--');
            $this->writeLine($handle, "-- Indexes for table `{$table}`");
            $this->writeLine($handle, '--');

            // Group: regular indexes vs fulltext
            $regularIndexes = [];
            $fulltextIndexes = [];

            foreach ($indexes as $idx) {
                if ($idx['type'] === 'FULLTEXT') {
                    $fulltextIndexes[] = $idx;
                } else {
                    $regularIndexes[] = $idx;
                }
            }

            // Write regular indexes
            if (!empty($regularIndexes)) {
                $lines = [];
                foreach ($regularIndexes as $idx) {
                    $cols = '(`' . implode('`,`', $idx['columns']) . '`)';

                    if ($idx['type'] === 'PRIMARY') {
                        $lines[] = "  ADD PRIMARY KEY {$cols}";
                    } elseif ($idx['type'] === 'UNIQUE') {
                        $lines[] = "  ADD UNIQUE KEY `{$idx['name']}` {$cols}";
                    } else {
                        $lines[] = "  ADD KEY `{$idx['name']}` {$cols}";
                    }
                }

                $this->writeLine($handle, "ALTER TABLE `{$table}`");
                $this->writeLine($handle, implode(",\n", $lines) . ';');
            }

            // Write fulltext indexes (separate ALTER TABLE statements)
            foreach ($fulltextIndexes as $idx) {
                $cols = '(`' . implode('`,`', $idx['columns']) . '`)';
                $this->writeLine($handle, "ALTER TABLE `{$table}` ADD FULLTEXT KEY `{$idx['name']}` {$cols};");
            }
        }
    }

    // ─── Auto Increment ──────────────────────────────────────

    protected function writeAutoIncrements($handle, array $tableAutoIncrements): void
    {
        $this->writeLine($handle, '');
        $this->writeLine($handle, '--');
        $this->writeLine($handle, '-- AUTO_INCREMENT for dumped tables');
        $this->writeLine($handle, '--');

        foreach ($tableAutoIncrements as $table => $info) {
            $this->writeLine($handle, '');
            $this->writeLine($handle, '--');
            $this->writeLine($handle, "-- AUTO_INCREMENT for table `{$table}`");
            $this->writeLine($handle, '--');

            $column = $info['column'];
            $colType = $info['column_type'];
            $autoIncValue = $info['auto_increment'];

            $autoIncSuffix = $autoIncValue ? ", AUTO_INCREMENT={$autoIncValue}" : '';

            $this->writeLine($handle, "ALTER TABLE `{$table}`");
            $this->writeLine($handle, "  MODIFY `{$column}` {$colType} NOT NULL AUTO_INCREMENT{$autoIncSuffix};");
        }
    }

    // ─── Constraints ─────────────────────────────────────────

    protected function writeConstraints($handle, array $tableConstraints): void
    {
        $this->writeLine($handle, '');
        $this->writeLine($handle, '--');
        $this->writeLine($handle, '-- Constraints for dumped tables');
        $this->writeLine($handle, '--');

        foreach ($tableConstraints as $table => $constraints) {
            $this->writeLine($handle, '');
            $this->writeLine($handle, '--');
            $this->writeLine($handle, "-- Constraints for table `{$table}`");
            $this->writeLine($handle, '--');

            $lines = [];
            foreach ($constraints as $fk) {
                $line = "  ADD CONSTRAINT `{$fk['name']}` FOREIGN KEY (`{$fk['column']}`) REFERENCES `{$fk['ref_table']}` (`{$fk['ref_column']}`)";

                if ($fk['on_delete'] && $fk['on_delete'] !== 'RESTRICT') {
                    $line .= ' ON DELETE ' . $fk['on_delete'];
                }
                if ($fk['on_update'] && $fk['on_update'] !== 'RESTRICT') {
                    $line .= ' ON UPDATE ' . $fk['on_update'];
                }

                $lines[] = $line;
            }

            $this->writeLine($handle, "ALTER TABLE `{$table}`");
            $this->writeLine($handle, implode(",\n", $lines) . ';');
        }
    }

    // ─── Footer ──────────────────────────────────────────────

    protected function writeFooter($handle): void
    {
        $this->writeLine($handle, 'COMMIT;');
        $this->writeLine($handle, '');
        $this->writeLine($handle, '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;');
        $this->writeLine($handle, '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;');
        $this->writeLine($handle, '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;');
    }

    // ─── Helpers ─────────────────────────────────────────────

    protected function getTables(): array
    {
        $database = config('database.connections.mysql.database');
        $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME", [$database]);

        return array_map(fn($t) => $t->TABLE_NAME, $tables);
    }

    protected function getColumnInfo(string $table): array
    {
        $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
        $info = [];
        foreach ($columns as $col) {
            $info[$col->Field] = $col->Type;
        }
        return $info;
    }

    protected function getIndexes(string $table): array
    {
        $database = config('database.connections.mysql.database');

        $rawIndexes = DB::select("
            SELECT
                s.INDEX_NAME,
                s.COLUMN_NAME,
                s.SEQ_IN_INDEX,
                s.NON_UNIQUE,
                s.INDEX_TYPE,
                s.SUB_PART
            FROM information_schema.STATISTICS s
            WHERE s.TABLE_SCHEMA = ?
              AND s.TABLE_NAME = ?
            ORDER BY s.INDEX_NAME, s.SEQ_IN_INDEX
        ", [$database, $table]);

        // Group by index name
        $grouped = [];
        foreach ($rawIndexes as $row) {
            $name = $row->INDEX_NAME;
            if (!isset($grouped[$name])) {
                $grouped[$name] = [
                    'name' => $name,
                    'columns' => [],
                    'non_unique' => $row->NON_UNIQUE,
                    'index_type' => $row->INDEX_TYPE,
                ];
            }
            $colRef = $row->COLUMN_NAME;
            if ($row->SUB_PART) {
                $colRef .= "({$row->SUB_PART})";
            }
            $grouped[$name]['columns'][] = $colRef;
        }

        // Classify index type
        $indexes = [];
        foreach ($grouped as $idx) {
            if ($idx['name'] === 'PRIMARY') {
                $type = 'PRIMARY';
            } elseif ($idx['index_type'] === 'FULLTEXT') {
                $type = 'FULLTEXT';
            } elseif ($idx['non_unique'] == 0) {
                $type = 'UNIQUE';
            } else {
                $type = 'INDEX';
            }

            $indexes[] = [
                'name' => $idx['name'],
                'type' => $type,
                'columns' => $idx['columns'],
            ];
        }

        return $indexes;
    }

    protected function getAutoIncrement(string $table): ?array
    {
        $database = config('database.connections.mysql.database');

        // Get auto increment column
        $aiColumn = DB::selectOne("
            SELECT COLUMN_NAME, COLUMN_TYPE
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = ?
              AND EXTRA LIKE '%auto_increment%'
        ", [$database, $table]);

        if (!$aiColumn) {
            return null;
        }

        // Get current auto_increment value
        $tableStatus = DB::selectOne("
            SELECT AUTO_INCREMENT
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = ?
        ", [$database, $table]);

        return [
            'column' => $aiColumn->COLUMN_NAME,
            'column_type' => $aiColumn->COLUMN_TYPE,
            'auto_increment' => $tableStatus->AUTO_INCREMENT,
        ];
    }

    protected function getConstraints(string $table): array
    {
        $database = config('database.connections.mysql.database');

        $fks = DB::select("
            SELECT
                kcu.CONSTRAINT_NAME,
                kcu.COLUMN_NAME,
                kcu.REFERENCED_TABLE_NAME,
                kcu.REFERENCED_COLUMN_NAME,
                rc.DELETE_RULE,
                rc.UPDATE_RULE
            FROM information_schema.KEY_COLUMN_USAGE kcu
            JOIN information_schema.REFERENTIAL_CONSTRAINTS rc
              ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
              AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
            WHERE kcu.TABLE_SCHEMA = ?
              AND kcu.TABLE_NAME = ?
              AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
            ORDER BY kcu.CONSTRAINT_NAME
        ", [$database, $table]);

        return array_map(fn($fk) => [
            'name' => $fk->CONSTRAINT_NAME,
            'column' => $fk->COLUMN_NAME,
            'ref_table' => $fk->REFERENCED_TABLE_NAME,
            'ref_column' => $fk->REFERENCED_COLUMN_NAME,
            'on_delete' => $fk->DELETE_RULE,
            'on_update' => $fk->UPDATE_RULE,
        ], $fks);
    }

    protected function cleanupOldBackups(string $dir, int $maxFiles): void
    {
        $sqlFiles = glob("{$dir}/*.sql");
        $gzFiles = glob("{$dir}/*.sql.gz");
        $allBackups = array_merge($sqlFiles, $gzFiles);

        if (count($allBackups) <= $maxFiles) {
            return;
        }

        // Sort by modification time (oldest first)
        usort($allBackups, fn($a, $b) => filemtime($a) - filemtime($b));

        $toDelete = array_slice($allBackups, 0, count($allBackups) - $maxFiles);
        foreach ($toDelete as $file) {
            unlink($file);
            $this->line("  Deleted old backup: " . basename($file));
        }
    }

    protected function compressFile(string $source, string $dest): void
    {
        $fp = fopen($source, 'rb');
        $gz = gzopen($dest, 'wb9');

        while (!feof($fp)) {
            gzwrite($gz, fread($fp, 1024 * 512));
        }

        fclose($fp);
        gzclose($gz);
    }

    protected function writeLine($handle, string $line): void
    {
        fwrite($handle, $line . "\n");
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
