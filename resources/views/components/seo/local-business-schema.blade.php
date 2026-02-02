{{-- Local Business Schema Markup for SEO --}}
@php
    $settingService = app(\App\Services\SettingService::class);
    $companyInfo = $settingService->getCompanyInfo();
    $contactInfo = $settingService->getContactInfo();
    $socialMedia = $settingService->getSocialMediaLinks();
    
    $localConfig = config('seo.local');
    $schemaConfig = config('seo.schema');
    
    // Build social media URLs
    $sameAs = [];
    if (!empty($socialMedia['facebook'])) $sameAs[] = $socialMedia['facebook'];
    if (!empty($socialMedia['instagram'])) $sameAs[] = $socialMedia['instagram'];
    if (!empty($socialMedia['linkedin'])) $sameAs[] = $socialMedia['linkedin'];
    if (!empty($socialMedia['youtube'])) $sameAs[] = $socialMedia['youtube'];
    if (!empty($socialMedia['tiktok'])) $sameAs[] = $socialMedia['tiktok'];
    
    $localBusinessSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'additionalType' => 'https://schema.org/WholesaleStore',
        '@id' => url('/') . '#localbusiness',
        'name' => $companyInfo['company_name'] ?? config('app.name'),
        'description' => 'Supplier dan distributor besi baja terpercaya di ' . implode(', ', array_slice($localConfig['service_areas'] ?? [], 0, 5)) . ' & seluruh ' . ($localConfig['province'] ?? 'Jawa Timur') . '. Melayani industri, manufaktur & konstruksi.',
        'url' => url('/'),
        'telephone' => $contactInfo['phone'] ?? '',
        'email' => $contactInfo['email'] ?? '',
        'priceRange' => '$$',
        'currenciesAccepted' => 'IDR',
        'paymentAccepted' => 'Cash, Bank Transfer',
        'openingHoursSpecification' => [
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '08:00',
                'closes' => '17:00',
            ],
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => 'Saturday',
                'opens' => '08:00',
                'closes' => '14:00',
            ],
        ],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $contactInfo['address'] ?? '',
            'addressLocality' => $localConfig['primary_city'] ?? 'Surabaya',
            'addressRegion' => $localConfig['province'] ?? 'Jawa Timur',
            'addressCountry' => 'ID',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => $localConfig['geo']['latitude'] ?? '-7.2575',
            'longitude' => $localConfig['geo']['longitude'] ?? '112.7521',
        ],
        'areaServed' => array_map(function($city) {
            return [
                '@type' => 'City',
                'name' => $city,
            ];
        }, $localConfig['service_areas'] ?? []),
        'sameAs' => $sameAs,
        'image' => !empty($companyInfo['logo']) ? Storage::url($companyInfo['logo']) : asset('images/logo.png'),
        'logo' => !empty($companyInfo['logo']) ? Storage::url($companyInfo['logo']) : asset('images/logo.png'),
    ];
    
    // Add province-level service area
    $localBusinessSchema['areaServed'][] = [
        '@type' => 'AdministrativeArea',
        'name' => $localConfig['province'] ?? 'Jawa Timur',
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
