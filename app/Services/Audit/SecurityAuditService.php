<?php

namespace App\Services\Audit;

use App\Models\AuditResult;
use App\Models\AuditIssue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SecurityAuditService
{
    protected array $issues = [];
    protected array $headers = [];
    protected ?string $html = null;

    /**
     * Run complete security audit for a URL
     */
    public function audit(string $url): AuditResult
    {
        $this->issues = [];
        $startTime = microtime(true);

        // Fetch page and headers
        $this->fetchPage($url);

        // Run all security checks
        $headerAnalysis = $this->analyzeSecurityHeaders();
        $sslAnalysis = $this->analyzeSsl($url);
        $exposureAnalysis = $this->analyzeExposure($url);
        $contentAnalysis = $this->analyzeContent();
        $configAnalysis = $this->analyzeConfiguration($url);

        // Calculate security score
        $securityScore = $this->calculateSecurityScore();

        $executionTime = round((microtime(true) - $startTime) * 1000);

        $rawData = [
            'headers' => $headerAnalysis,
            'ssl' => $sslAnalysis,
            'exposure' => $exposureAnalysis,
            'content' => $contentAnalysis,
            'config' => $configAnalysis,
        ];

        // Save result
        $auditResult = AuditResult::create([
            'url' => $url,
            'page_type' => 'security',
            'audit_type' => 'security',
            'best_practices_score' => $securityScore,
            'raw_data' => $rawData,
            'source' => 'manual',
            'notes' => "Security audit completed in {$executionTime}ms. Found " . count($this->issues) . " issues.",
        ]);

        // Save issues
        foreach ($this->issues as $issue) {
            $issue['audit_result_id'] = $auditResult->id;
            AuditIssue::create($issue);
        }

        return $auditResult;
    }

    /**
     * Fetch page and store headers
     */
    protected function fetchPage(string $url): void
    {
        try {
            $http = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'BMG-Security-Audit/1.0',
                ]);
            
            // Disable SSL verification for local URLs
            if (str_contains($url, 'localhost') || str_contains($url, '.test') || str_contains($url, '.local')) {
                $http = $http->withoutVerifying();
            }
            
            $response = $http->get($url);

            $this->headers = $response->headers();
            $this->html = $response->body();
        } catch (\Exception $e) {
            Log::error('Security audit fetch failed', ['url' => $url, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Analyze security headers
     */
    protected function analyzeSecurityHeaders(): array
    {
        $requiredHeaders = [
            'Content-Security-Policy' => [
                'severity' => 'critical',
                'description' => 'CSP helps prevent XSS, clickjacking, and other code injection attacks.',
                'suggestion' => "Add Content-Security-Policy header to control resource loading.",
            ],
            'X-Frame-Options' => [
                'severity' => 'critical',
                'description' => 'Prevents clickjacking attacks by disabling iframe embedding.',
                'suggestion' => "Add X-Frame-Options: SAMEORIGIN header.",
            ],
            'X-Content-Type-Options' => [
                'severity' => 'warning',
                'description' => 'Prevents MIME type sniffing.',
                'suggestion' => "Add X-Content-Type-Options: nosniff header.",
            ],
            'X-XSS-Protection' => [
                'severity' => 'info',
                'description' => 'Enables browser XSS filtering (legacy, but still useful).',
                'suggestion' => "Add X-XSS-Protection: 1; mode=block header.",
            ],
            'Strict-Transport-Security' => [
                'severity' => 'critical',
                'description' => 'HSTS ensures HTTPS-only connections.',
                'suggestion' => "Add Strict-Transport-Security: max-age=31536000; includeSubDomains header.",
            ],
            'Referrer-Policy' => [
                'severity' => 'warning',
                'description' => 'Controls referrer information sent with requests.',
                'suggestion' => "Add Referrer-Policy: strict-origin-when-cross-origin header.",
            ],
            'Permissions-Policy' => [
                'severity' => 'info',
                'description' => 'Controls browser features and APIs.',
                'suggestion' => "Add Permissions-Policy header to restrict unnecessary features.",
            ],
        ];

        $analysis = [
            'present' => [],
            'missing' => [],
        ];

        foreach ($requiredHeaders as $header => $config) {
            $headerValue = $this->getHeader($header);
            
            if ($headerValue) {
                $analysis['present'][$header] = $headerValue;
            } else {
                $analysis['missing'][] = $header;
                $this->addIssue([
                    'type' => 'security',
                    'severity' => $config['severity'],
                    'category' => 'headers',
                    'title' => "Missing {$header} header",
                    'description' => $config['description'],
                    'suggestion' => $config['suggestion'],
                    'impact_score' => $config['severity'] === 'critical' ? 0.8 : ($config['severity'] === 'warning' ? 0.5 : 0.3),
                ]);
            }
        }

        // Check for insecure headers
        $serverHeader = $this->getHeader('Server');
        if ($serverHeader && Str::contains(strtolower($serverHeader), ['apache/', 'nginx/', 'php/', 'iis/'])) {
            $this->addIssue([
                'type' => 'security',
                'severity' => 'info',
                'category' => 'headers',
                'title' => 'Server version disclosure',
                'description' => "Server header reveals: {$serverHeader}",
                'suggestion' => 'Configure server to hide version information.',
                'element' => $serverHeader,
                'impact_score' => 0.3,
            ]);
        }

        $xPoweredBy = $this->getHeader('X-Powered-By');
        if ($xPoweredBy) {
            $this->addIssue([
                'type' => 'security',
                'severity' => 'warning',
                'category' => 'headers',
                'title' => 'Technology stack disclosure',
                'description' => "X-Powered-By header reveals: {$xPoweredBy}",
                'suggestion' => 'Remove X-Powered-By header to hide technology stack.',
                'element' => $xPoweredBy,
                'impact_score' => 0.4,
            ]);
        }

        return $analysis;
    }

    /**
     * Analyze SSL/TLS
     */
    protected function analyzeSsl(string $url): array
    {
        $parsedUrl = parse_url($url);
        $isHttps = ($parsedUrl['scheme'] ?? '') === 'https';
        $host = $parsedUrl['host'] ?? '';

        $analysis = [
            'is_https' => $isHttps,
            'certificate' => null,
        ];

        if (!$isHttps) {
            $this->addIssue([
                'type' => 'security',
                'severity' => 'critical',
                'category' => 'ssl',
                'title' => 'Not using HTTPS',
                'description' => 'The site is not using HTTPS encryption.',
                'suggestion' => 'Install an SSL certificate and redirect all HTTP traffic to HTTPS.',
                'impact_score' => 1.0,
            ]);
            return $analysis;
        }

        // Check SSL certificate
        try {
            $context = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                ],
            ]);

            $socket = @stream_socket_client(
                "ssl://{$host}:443",
                $errno,
                $errstr,
                30,
                STREAM_CLIENT_CONNECT,
                $context
            );

            if ($socket) {
                $params = stream_context_get_params($socket);
                $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate'] ?? '');
                
                if ($cert) {
                    $validTo = $cert['validTo_time_t'] ?? 0;
                    $daysLeft = floor(($validTo - time()) / 86400);

                    $analysis['certificate'] = [
                        'issuer' => $cert['issuer']['O'] ?? 'Unknown',
                        'valid_from' => date('Y-m-d', $cert['validFrom_time_t'] ?? 0),
                        'valid_to' => date('Y-m-d', $validTo),
                        'days_until_expiry' => $daysLeft,
                    ];

                    if ($daysLeft < 30) {
                        $this->addIssue([
                            'type' => 'security',
                            'severity' => $daysLeft < 7 ? 'critical' : 'warning',
                            'category' => 'ssl',
                            'title' => 'SSL certificate expiring soon',
                            'description' => "Certificate expires in {$daysLeft} days.",
                            'suggestion' => 'Renew SSL certificate before expiration.',
                            'impact_score' => $daysLeft < 7 ? 0.9 : 0.5,
                        ]);
                    }
                }

                fclose($socket);
            }
        } catch (\Exception $e) {
            $analysis['certificate'] = ['error' => $e->getMessage()];
        }

        return $analysis;
    }

    /**
     * Analyze exposure of sensitive files/paths
     */
    protected function analyzeExposure(string $url): array
    {
        $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
        
        $sensitiveFiles = [
            '/.env' => 'Environment configuration file',
            '/.git/config' => 'Git repository configuration',
            '/wp-config.php' => 'WordPress configuration',
            '/config.php' => 'Configuration file',
            '/phpinfo.php' => 'PHP info page',
            '/debug' => 'Debug page',
            '/composer.json' => 'Composer dependencies',
            '/composer.lock' => 'Composer lock file',
            '/.htpasswd' => 'Password file',
            '/backup.sql' => 'Database backup',
            '/database.sql' => 'Database file',
            '/admin' => 'Admin panel',
        ];

        $analysis = [
            'exposed' => [],
            'checked' => [],
        ];

        foreach ($sensitiveFiles as $path => $description) {
            $testUrl = $baseUrl . $path;
            
            try {
                $response = Http::timeout(5)
                    ->withHeaders(['User-Agent' => 'BMG-Security-Audit/1.0'])
                    ->get($testUrl);

                $analysis['checked'][] = $path;

                // Check if file is accessible (not 404 or 403)
                if ($response->status() === 200) {
                    $contentType = $response->header('Content-Type') ?? '';
                    
                    // Skip if it returns HTML (likely a custom 404 or redirect)
                    if (!Str::contains($contentType, 'text/html') || 
                        Str::contains(strtolower($response->body()), ['db_password', 'app_key', 'api_key', 'secret'])) {
                        
                        $analysis['exposed'][] = $path;
                        $this->addIssue([
                            'type' => 'security',
                            'severity' => 'critical',
                            'category' => 'exposure',
                            'title' => "Sensitive file exposed: {$path}",
                            'description' => "{$description} is publicly accessible.",
                            'suggestion' => "Block access to {$path} in web server configuration.",
                            'element' => $testUrl,
                            'impact_score' => 1.0,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Timeout or connection error - file probably not accessible
                continue;
            }
        }

        return $analysis;
    }

    /**
     * Analyze content for security issues
     */
    protected function analyzeContent(): array
    {
        if (!$this->html) {
            return ['analyzed' => false];
        }

        $analysis = [
            'analyzed' => true,
            'issues' => [],
        ];

        // Check for inline JavaScript with sensitive patterns
        $dangerousPatterns = [
            'eval(' => 'Use of eval() is dangerous',
            'document.write(' => 'document.write() can be exploited',
            'innerHTML' => 'innerHTML can lead to XSS if user input is used',
        ];

        foreach ($dangerousPatterns as $pattern => $description) {
            if (Str::contains($this->html, $pattern)) {
                $analysis['issues'][] = $pattern;
                // Only report as info since these are common and context-dependent
            }
        }

        // Check for sensitive data in HTML
        $sensitivePatterns = [
            '/api[_-]?key\s*[:=]\s*["\'][^"\']+["\']/i' => 'API key exposed in HTML',
            '/password\s*[:=]\s*["\'][^"\']+["\']/i' => 'Password exposed in HTML',
            '/secret\s*[:=]\s*["\'][^"\']+["\']/i' => 'Secret exposed in HTML',
        ];

        foreach ($sensitivePatterns as $regex => $description) {
            if (preg_match($regex, $this->html, $matches)) {
                $this->addIssue([
                    'type' => 'security',
                    'severity' => 'critical',
                    'category' => 'content',
                    'title' => $description,
                    'description' => 'Sensitive data may be exposed in page source.',
                    'suggestion' => 'Remove sensitive data from client-side code.',
                    'impact_score' => 1.0,
                ]);
            }
        }

        // Check for forms without CSRF
        $doc = new \DOMDocument();
        @$doc->loadHTML($this->html, LIBXML_NOERROR);
        $xpath = new \DOMXPath($doc);
        $forms = $xpath->query('//form[@method="post" or @method="POST"]');

        foreach ($forms as $form) {
            $hasToken = false;
            $inputs = $xpath->query('.//input[@name="_token" or @name="csrf_token" or @name="_csrf"]', $form);
            
            if ($inputs->length === 0) {
                $action = $form->getAttribute('action');
                $this->addIssue([
                    'type' => 'security',
                    'severity' => 'warning',
                    'category' => 'csrf',
                    'title' => 'Form missing CSRF token',
                    'description' => 'A POST form does not appear to have CSRF protection.',
                    'suggestion' => 'Add @csrf directive to all forms in Laravel.',
                    'element' => $action ?: 'inline form',
                    'impact_score' => 0.6,
                ]);
            }
        }

        // Check for mixed content
        if (Str::contains($this->html, 'http://') && !Str::contains(strtolower($this->html), 'http://localhost')) {
            preg_match_all('/src=["\']http:\/\/[^"\']+["\']/', $this->html, $matches);
            if (!empty($matches[0])) {
                $this->addIssue([
                    'type' => 'security',
                    'severity' => 'warning',
                    'category' => 'mixed_content',
                    'title' => 'Mixed content detected',
                    'description' => 'Page loads resources over insecure HTTP.',
                    'suggestion' => 'Update all resource URLs to use HTTPS.',
                    'element' => implode(', ', array_slice($matches[0], 0, 3)),
                    'impact_score' => 0.5,
                ]);
            }
        }

        return $analysis;
    }

    /**
     * Analyze application configuration
     */
    protected function analyzeConfiguration(string $url): array
    {
        $analysis = [
            'debug_mode' => false,
            'error_display' => false,
        ];

        // Check for debug mode indicators in response
        if ($this->html) {
            $debugIndicators = [
                'Whoops!' => 'Laravel debug mode is enabled',
                'Stack Trace' => 'Stack traces are visible',
                'APP_DEBUG' => 'Debug configuration exposed',
                'SQLSTATE' => 'Database errors are visible',
                'PDOException' => 'Database exceptions are visible',
            ];

            foreach ($debugIndicators as $indicator => $description) {
                if (Str::contains($this->html, $indicator)) {
                    $analysis['debug_mode'] = true;
                    $this->addIssue([
                        'type' => 'security',
                        'severity' => 'critical',
                        'category' => 'configuration',
                        'title' => 'Debug mode appears to be enabled',
                        'description' => $description,
                        'suggestion' => 'Set APP_DEBUG=false in production environment.',
                        'impact_score' => 0.9,
                    ]);
                    break;
                }
            }
        }

        // Check if directory listing is enabled
        $testDirs = ['/storage', '/uploads', '/images'];
        foreach ($testDirs as $dir) {
            try {
                $response = Http::timeout(5)->get(parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . $dir);
                if ($response->successful() && Str::contains($response->body(), ['Index of', 'Directory listing'])) {
                    $this->addIssue([
                        'type' => 'security',
                        'severity' => 'warning',
                        'category' => 'configuration',
                        'title' => "Directory listing enabled: {$dir}",
                        'description' => 'Directory contents are publicly visible.',
                        'suggestion' => 'Disable directory listing in web server configuration.',
                        'impact_score' => 0.5,
                    ]);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return $analysis;
    }

    /**
     * Calculate security score
     */
    protected function calculateSecurityScore(): int
    {
        $score = 100;
        
        foreach ($this->issues as $issue) {
            $deduction = match ($issue['severity']) {
                'critical' => 20,
                'warning' => 10,
                'info' => 3,
                default => 0,
            };
            $score -= $deduction;
        }

        return max(0, min(100, $score));
    }

    /**
     * Get header value
     */
    protected function getHeader(string $name): ?string
    {
        $lowercase = strtolower($name);
        
        foreach ($this->headers as $key => $values) {
            if (strtolower($key) === $lowercase) {
                return is_array($values) ? $values[0] : $values;
            }
        }
        
        return null;
    }

    /**
     * Add issue
     */
    protected function addIssue(array $issue): void
    {
        $issue['status'] = 'open';
        $this->issues[] = $issue;
    }

    /**
     * Get recommended security headers for web server
     */
    public static function getRecommendedHeaders(): array
    {
        return [
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Content-Security-Policy' => implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.googletagmanager.com",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
                "img-src 'self' data: https: blob:",
                "font-src 'self' https://fonts.gstatic.com",
                "frame-src 'self' https://www.youtube.com https://www.google.com",
                "connect-src 'self'",
                "object-src 'none'",
                "base-uri 'self'",
            ]),
        ];
    }
}
