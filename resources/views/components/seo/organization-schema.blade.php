{{-- Organization Schema Markup for SEO --}}
@php
    $settingService = app(\App\Services\SettingService::class);
    $companyInfo = $settingService->getCompanyInfo();
    $contactInfo = $settingService->getContactInfo();
    $socialMedia = $settingService->getSocialMediaLinks();
    
    $localConfig = config('seo.local');
    
    // Build social media URLs
    $sameAs = [];
    if (!empty($socialMedia['facebook'])) $sameAs[] = $socialMedia['facebook'];
    if (!empty($socialMedia['instagram'])) $sameAs[] = $socialMedia['instagram'];
    if (!empty($socialMedia['linkedin'])) $sameAs[] = $socialMedia['linkedin'];
    if (!empty($socialMedia['youtube'])) $sameAs[] = $socialMedia['youtube'];
    if (!empty($socialMedia['tiktok'])) $sameAs[] = $socialMedia['tiktok'];
    
    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => url('/') . '#organization',
        'name' => $companyInfo['company_name'] ?? config('app.name'),
        'legalName' => $companyInfo['company_name'] ?? config('app.name'),
        'description' => 'Supplier dan distributor besi baja terpercaya di Surabaya, Sidoarjo, Gresik, Mojokerto, Jombang & seluruh Jawa Timur untuk industri, manufaktur & konstruksi.',
        'url' => url('/'),
        'logo' => !empty($companyInfo['logo']) ? Storage::url($companyInfo['logo']) : asset('images/logo.png'),
        'image' => !empty($companyInfo['logo']) ? Storage::url($companyInfo['logo']) : asset('images/logo.png'),
        'telephone' => $contactInfo['phone'] ?? '',
        'email' => $contactInfo['email'] ?? '',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $contactInfo['address'] ?? '',
            'addressLocality' => $localConfig['primary_city'] ?? 'Surabaya',
            'addressRegion' => $localConfig['province'] ?? 'Jawa Timur',
            'addressCountry' => 'ID',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => $contactInfo['phone'] ?? '',
            'contactType' => 'customer service',
            'availableLanguage' => ['Indonesian', 'English'],
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => 'Jawa Timur',
            ],
        ],
        'areaServed' => array_merge(
            array_map(function($city) {
                return ['@type' => 'City', 'name' => $city];
            }, $localConfig['service_areas'] ?? []),
            [['@type' => 'AdministrativeArea', 'name' => 'Jawa Timur']]
        ),
        'sameAs' => $sameAs,
        'knowsAbout' => [
            'Besi Baja',
            'Konstruksi',
            'Industri Manufaktur',
            'Material Bangunan',
        ],
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
