<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = array_keys(config('app.supported_locales', []));
        $fallbackLocale = config('app.locale', 'en');
        $locale = $request->session()->get('locale', $fallbackLocale);

        if (!in_array($locale, $supportedLocales, true)) {
            $locale = $fallbackLocale;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
