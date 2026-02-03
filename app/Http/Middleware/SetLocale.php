<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['id', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: query param > session > cookie > default
        $locale = $request->query('lang')
            ?? Session::get('locale') 
            ?? $request->cookie('locale') 
            ?? config('app.locale', 'id');

        if (!in_array($locale, $this->supportedLocales)) {
            $locale = 'id';
        }

        // If locale came from query param, store it
        if ($request->query('lang') && in_array($request->query('lang'), $this->supportedLocales)) {
            Session::put('locale', $locale);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
