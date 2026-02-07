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
        $separator = str_contains($previousUrl, '?') ? '&' : '?';
        $redirectUrl = $previousUrl . $separator . 'lang=' . $locale;

        return redirect($redirectUrl)
            ->withCookie($cookie)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
