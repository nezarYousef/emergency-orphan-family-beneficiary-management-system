<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#102a43">
    <title>{{ __('auth.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap{{ app()->getLocale() === 'ar' ? '.rtl' : '' }}.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="auth-page" data-saving-label="{{ __('common.saving') }}">
    <a class="skip-link" href="#main-content">{{ __('auth.skip_to_sign_in') }}</a>
    <main id="main-content" class="auth-shell" tabindex="-1">
        <section class="auth-story" aria-labelledby="auth-story-title">
            <a class="brand-mark" href="{{ route('home') }}" aria-label="{{ __('auth.home_label') }}"><span class="brand-icon" aria-hidden="true">+</span><span><strong>{{ __('common.app_name') }}</strong><small>{{ __('auth.operations') }}</small></span></a>
            <div><p class="eyebrow text-white-50"><span class="eyebrow-line" aria-hidden="true"></span> {{ __('auth.secure_workspace') }}</p><h1 id="auth-story-title">{{ __('auth.story_title') }}</h1><p>{{ __('auth.story_description') }}</p></div>
            <p class="mb-0 small text-white-50">{{ __('auth.demo_notice') }}</p>
        </section>
        <section class="auth-panel" aria-labelledby="sign-in-title">
            <div class="auth-card">
                <div class="auth-language"><x-language-switcher /></div>
                <a class="d-inline-block mb-3 text-decoration-none" href="{{ route('home') }}"><span class="directional-icon" aria-hidden="true">←</span> {{ __('auth.back_home') }}</a>
                <h2 id="sign-in-title">{{ __('auth.welcome_back') }}</h2>
                <p class="text-muted mb-4">{{ __('auth.sign_in_description') }}</p>
                @if ($errors->any())<div class="alert alert-danger" role="alert"><strong>{{ __('auth.unable_to_sign_in') }}</strong> {{ $errors->first() }}</div>@endif
                <form method="post" action="{{ route('login') }}" data-no-loading>
                    @csrf
                    <div class="mb-3"><label class="form-label" for="email">{{ __('auth.email') }} <span class="required-mark" aria-hidden="true">*</span></label><input class="form-control" id="email" name="email" type="email" dir="ltr" value="{{ old('email') }}" autocomplete="username" required aria-describedby="email-help"><div id="email-help" class="form-text">{{ __('auth.email_help') }}</div></div>
                    <div class="mb-3"><label class="form-label" for="password">{{ __('auth.password_label') }} <span class="required-mark" aria-hidden="true">*</span></label><input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required></div>
                    <button class="btn btn-primary btn-lg w-100" type="submit">{{ __('auth.sign_in') }} <span class="directional-icon" aria-hidden="true">→</span></button>
                </form>
                <div class="demo-note mt-4" role="note"><strong>{{ __('auth.demo_access') }}</strong><br><bdi dir="ltr">admin@example.com · password</bdi><br><bdi dir="ltr">dataentry@example.com · password</bdi><br><bdi dir="ltr">viewer@example.com · password</bdi></div>
            </div>
        </section>
    </main>
</body>
</html>
