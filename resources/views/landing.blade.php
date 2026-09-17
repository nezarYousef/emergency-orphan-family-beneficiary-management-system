<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#102a43">
    <meta name="description" content="{{ __('landing.meta_description') }}">
    <title>{{ __('landing.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap{{ app()->getLocale() === 'ar' ? '.rtl' : '' }}.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="landing-page">
    <a class="skip-link" href="#main-content">{{ __('landing.skip_to_content') }}</a>
    <header class="landing-nav">
        <a class="brand-mark brand-mark-dark" href="{{ route('home') }}" aria-label="{{ __('landing.home_label') }}"><span class="brand-icon" aria-hidden="true">+</span><span><strong>CareTrack</strong><small>{{ __('landing.operations') }}</small></span></a>
        <nav class="landing-nav-links" aria-label="{{ __('landing.navigation_label') }}">
            <a href="#approach">{{ __('landing.approach_link') }}</a>
            <a href="#capabilities">{{ __('landing.capabilities_link') }}</a>
            <x-language-switcher />
            <a class="btn btn-sm btn-light px-3" href="{{ route('login') }}">{{ __('landing.sign_in') }}</a>
        </nav>
    </header>
    <main id="main-content">
        <section class="landing-hero">
            <div class="container-xl">
                <div class="row align-items-center gx-4 gy-5">
                    <div class="col-lg-7">
                        <p class="eyebrow"><span class="eyebrow-line"></span> {{ __('landing.hero_eyebrow') }}</p>
                        <h1>{{ __('landing.hero_title') }} <span>{{ __('landing.hero_title_highlight') }}</span></h1>
                        <p class="hero-lead">{{ __('landing.hero_description') }}</p>
                        <div class="hero-actions"><a class="btn btn-primary btn-lg" href="{{ route('login') }}">{{ __('landing.open_workspace') }} <span aria-hidden="true">{{ app()->getLocale() === 'ar' ? '←' : '→' }}</span></a><a class="btn btn-link btn-lg" href="#approach">{{ __('landing.see_how') }} <span aria-hidden="true">↓</span></a></div>
                        <div class="hero-trust"><span class="trust-check" aria-hidden="true">✓</span><span>{{ __('landing.secure_access') }}</span><span class="trust-divider" aria-hidden="true"></span><span>{{ __('landing.database_records') }}</span></div>
                    </div>
                    <div class="col-lg-5">
                        <div class="hero-humanitarian-visual">
                            <img src="{{ asset('images/humanitarian/hero-family-support.webp') }}" width="560" height="448" fetchpriority="high" alt="{{ __('landing.hero_alt') }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="landing-section" aria-labelledby="impact-heading">
            <div class="container-xl">
                <div class="section-heading">
                    <h2 id="impact-heading">{{ __('landing.impact_title') }}</h2>
                    <p>{{ __('landing.impact_description') }}</p>
                </div>
                <div class="humanitarian-grid mt-5">
                    <article class="humanitarian-card">
                        <img src="{{ asset('images/humanitarian/aid-distribution.webp') }}" width="488" height="366" loading="lazy" alt="{{ __('landing.assistance_alt') }}">
                        <div class="humanitarian-card-content">
                            <p class="eyebrow">{{ __('landing.assistance_category') }}</p>
                            <h3>{{ __('landing.assistance_title') }}</h3>
                            <p>{{ __('landing.assistance_description') }}</p>
                        </div>
                    </article>
                    <article class="humanitarian-card">
                        <img src="{{ asset('images/humanitarian/family-support.webp') }}" width="488" height="366" loading="lazy" alt="{{ __('landing.family_alt') }}">
                        <div class="humanitarian-card-content">
                            <p class="eyebrow">{{ __('landing.family_category') }}</p>
                            <h3>{{ __('landing.family_title') }}</h3>
                            <p>{{ __('landing.family_description') }}</p>
                        </div>
                    </article>
                    <article class="humanitarian-card">
                        <img src="{{ asset('images/humanitarian/child-future.webp') }}" width="488" height="366" loading="lazy" alt="{{ __('landing.child_alt') }}">
                        <div class="humanitarian-card-content">
                            <p class="eyebrow">{{ __('landing.child_category') }}</p>
                            <h3>{{ __('landing.child_title') }}</h3>
                            <p>{{ __('landing.child_description') }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>
        <section id="approach" class="landing-section section-soft">
            <div class="container-xl">
                <div class="section-heading"><p class="eyebrow">{{ __('landing.approach_eyebrow') }}</p><h2>{{ __('landing.approach_title') }}</h2><p>{{ __('landing.approach_description') }}</p></div>
                <div class="row g-4 mt-2">
                    <div class="col-md-4"><article class="feature-card"><span class="feature-number">01</span><h3>{{ __('landing.story_title') }}</h3><p>{{ __('landing.story_description') }}</p></article></div>
                    <div class="col-md-4"><article class="feature-card"><span class="feature-number">02</span><h3>{{ __('landing.confidence_title') }}</h3><p>{{ __('landing.confidence_description') }}</p></article></div>
                    <div class="col-md-4"><article class="feature-card"><span class="feature-number">03</span><h3>{{ __('landing.accountability_title') }}</h3><p>{{ __('landing.accountability_description') }}</p></article></div>
                </div>
            </div>
        </section>
        <section id="capabilities" class="landing-section">
            <div class="container-xl">
                <div class="row align-items-end g-4"><div class="col-lg-7"><p class="eyebrow">{{ __('landing.capabilities_eyebrow') }}</p><h2>{{ __('landing.capabilities_title') }}</h2></div><div class="col-lg-5"><p class="section-copy">{{ __('landing.capabilities_description') }}</p></div></div>
                <div class="capability-grid mt-5">
                    <div class="capability-item"><span aria-hidden="true">⌂</span><div><h3>{{ __('landing.dashboard_title') }}</h3><p>{{ __('landing.dashboard_description') }}</p></div></div>
                    <div class="capability-item"><span aria-hidden="true">◌</span><div><h3>{{ __('landing.records_title') }}</h3><p>{{ __('landing.records_description') }}</p></div></div>
                    <div class="capability-item"><span aria-hidden="true">▣</span><div><h3>{{ __('landing.aid_title') }}</h3><p>{{ __('landing.aid_description') }}</p></div></div>
                    <div class="capability-item"><span aria-hidden="true">⌁</span><div><h3>{{ __('landing.access_title') }}</h3><p>{{ __('landing.access_description') }}</p></div></div>
                </div>
            </div>
        </section>
        <section class="landing-cta"><div class="container-xl d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4"><div><p class="eyebrow text-white-50 mb-2">{{ __('landing.cta_eyebrow') }}</p><h2>{{ __('landing.cta_title') }}</h2></div><a class="btn btn-light btn-lg" href="{{ route('login') }}">{{ __('landing.cta_link') }} <span aria-hidden="true">{{ app()->getLocale() === 'ar' ? '←' : '→' }}</span></a></div></section>
    </main>
    <footer class="landing-footer"><div class="container-xl d-flex flex-column flex-md-row justify-content-between gap-3"><span>© {{ now()->year }} {{ __('landing.copyright') }}</span><span>{{ __('landing.demo_notice') }}</span></div></footer>
</body>
</html>
