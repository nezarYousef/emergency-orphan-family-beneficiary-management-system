<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->hasSession() ? $request->session()->get('locale') : null;
        $locale = in_array($locale, ['en', 'ar'], true) ? $locale : 'en';

        app()->setLocale($locale);
        $request->setLocale($locale);

        return $next($request);
    }
}
