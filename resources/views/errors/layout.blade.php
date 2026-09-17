<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('errors.title') }} | {{ __('common.app_name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap{{ app()->getLocale() === 'ar' ? '.rtl' : '' }}.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="error-page" data-saving-label="{{ __('common.saving') }}">
    <a class="skip-link" href="#main-content">{{ __('common.skip_content') }}</a>
    <main id="main-content" class="error-card" aria-labelledby="error-title" tabindex="-1">
        <x-language-switcher />
        <div class="error-code" aria-hidden="true">{{ $code ?? '!' }}</div>
        <h1 id="error-title" class="h2">{{ $heading ?? __('errors.title') }}</h1>
        <p class="text-muted">{{ $message ?? __('errors.message') }}</p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a class="btn btn-primary" href="{{ auth()->check() ? route('dashboard') : route('home') }}">{{ auth()->check() ? __('errors.back_dashboard') : __('errors.back_home') }}</a>
            @if (auth()->check())<a class="btn btn-outline-secondary" href="{{ url()->previous() }}">{{ __('errors.go_back') }}</a>@endif
        </div>
    </main>
</body>
</html>
