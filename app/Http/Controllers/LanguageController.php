<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    protected array $supportedLocales = ['id', 'en'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = 'id';
        }

        // Store in both session and cookie for reliability
        Session::put('locale', $locale);
        app()->setLocale($locale);

        // Cookie lasts 1 year, httpOnly=false so JS can read it if needed
        $cookie = Cookie::make('locale', $locale, 60 * 24 * 365, '/', null, null, false);

        // Get previous URL and add cache-bust parameter to bypass Cloudflare cache
        $previousUrl = url()->previous();

        // Remove any trailing slashes after the domain path to prevent double-slash URLs
        $parsed = parse_url($previousUrl);
        $path = rtrim($parsed['path'] ?? '/', '/') ?: '/';
        $cleanUrl = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? request()->getHost()) . $path;
        if (!empty($parsed['query'])) {
            $cleanUrl .= '?' . $parsed['query'];
        }

        $separator = str_contains($cleanUrl, '?') ? '&' : '?';
        $redirectUrl = $cleanUrl . $separator . 'lang=' . $locale;

        return redirect($redirectUrl)
            ->withCookie($cookie)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
