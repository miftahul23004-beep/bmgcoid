<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    protected array $supportedLocales = ['id', 'en'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = 'id';
        }

        Session::put('locale', $locale);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
