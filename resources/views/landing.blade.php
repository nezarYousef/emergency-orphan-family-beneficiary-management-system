<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#102a43">
    <meta name="description" content="CareTrack helps humanitarian teams coordinate families, beneficiaries, orphan support, and aid distributions with clarity and accountability.">
    <title>CareTrack | Humanitarian Case Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="landing-page">
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header class="landing-nav">
        <a class="brand-mark brand-mark-dark" href="{{ route('home') }}" aria-label="CareTrack home"><span class="brand-icon" aria-hidden="true">+</span><span><strong>CareTrack</strong><small>Humanitarian operations</small></span></a>
        <nav class="landing-nav-links" aria-label="Public navigation"><a href="#approach">Our approach</a><a href="#capabilities">Capabilities</a><a class="btn btn-sm btn-light px-3" href="{{ route('login') }}">Sign in</a></nav>
    </header>
    <main id="main-content">
        <section class="landing-hero">
            <div class="container-xl">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7">
                        <p class="eyebrow"><span class="eyebrow-line"></span> Humanitarian case management</p>
                        <h1>Turn urgent needs into <span>coordinated care.</span></h1>
                        <p class="hero-lead">A focused workspace for teams supporting families, beneficiaries, and children in crisis—designed to keep every case visible, accountable, and moving forward.</p>
                        <div class="hero-actions"><a class="btn btn-primary btn-lg" href="{{ route('login') }}">Open the workspace <span aria-hidden="true">→</span></a><a class="btn btn-link btn-lg" href="#approach">See how it works <span aria-hidden="true">↓</span></a></div>
                        <div class="hero-trust"><span class="trust-check" aria-hidden="true">✓</span><span>Secure role-based access</span><span class="trust-divider" aria-hidden="true"></span><span>PostgreSQL-backed records</span></div>
                    </div>
                    <div class="col-lg-5">
                        <div class="hero-visual" aria-label="Illustration of a coordinated case dashboard">
                            <div class="visual-orbit orbit-one"></div><div class="visual-orbit orbit-two"></div>
                            <div class="visual-card visual-card-main"><div class="visual-card-header"><span class="mini-icon">✦</span><span>Case overview</span><span class="mini-status">Live</span></div><div class="visual-number">24<span> active families</span></div><div class="visual-progress"><span></span></div><div class="visual-caption"><span>Coverage this month</span><strong>82%</strong></div></div>
                            <div class="visual-card visual-card-float"><span class="float-icon">♡</span><div><strong>Orphan support</strong><small>18 cases need follow-up</small></div></div>
                            <div class="visual-dots" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="approach" class="landing-section section-soft">
            <div class="container-xl"><div class="section-heading"><p class="eyebrow">One clear operating picture</p><h2>Make every handoff count.</h2><p>CareTrack brings the information teams need into one calm, consistent workflow—without losing the human context behind each record.</p></div><div class="row g-4 mt-2"><div class="col-md-4"><article class="feature-card"><span class="feature-number">01</span><h3>See the full story</h3><p>Connect families, beneficiaries, orphan records, and aid history so teams can understand needs in context.</p></article></div><div class="col-md-4"><article class="feature-card"><span class="feature-number">02</span><h3>Act with confidence</h3><p>Validated forms, searchable lists, and role-aware workflows keep day-to-day decisions consistent.</p></article></div><div class="col-md-4"><article class="feature-card"><span class="feature-number">03</span><h3>Stay accountable</h3><p>Auditable changes and clear exports help organizations explain what happened and when.</p></article></div></div></div>
        </section>
        <section id="capabilities" class="landing-section">
            <div class="container-xl"><div class="row align-items-end g-4"><div class="col-lg-7"><p class="eyebrow">Built for the field</p><h2>Clarity for the people doing the work.</h2></div><div class="col-lg-5"><p class="section-copy">From first intake to follow-up, the workspace is structured around practical case-management moments.</p></div></div><div class="capability-grid mt-5"><div class="capability-item"><span>⌂</span><div><h3>Operational dashboard</h3><p>Live counts and recent activity from the connected PostgreSQL database.</p></div></div><div class="capability-item"><span>◌</span><div><h3>Family-centered records</h3><p>Keep household context connected to every beneficiary and service touchpoint.</p></div></div><div class="capability-item"><span>▣</span><div><h3>Aid coordination</h3><p>Record distributions with dates, references, providers, and linked cases.</p></div></div><div class="capability-item"><span>⌁</span><div><h3>Responsible access</h3><p>Admin, data-entry, and viewer roles keep sensitive actions in the right hands.</p></div></div></div></div>
        </section>
        <section class="landing-cta"><div class="container-xl d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4"><div><p class="eyebrow text-white-50 mb-2">Ready when your team is</p><h2>Start with a clearer case picture.</h2></div><a class="btn btn-light btn-lg" href="{{ route('login') }}">Sign in to CareTrack <span aria-hidden="true">→</span></a></div></section>
    </main>
    <footer class="landing-footer"><div class="container-xl d-flex flex-column flex-md-row justify-content-between gap-3"><span>© {{ now()->year }} CareTrack Humanitarian Operations</span><span>Demo system · Fictional data only</span></div></footer>
</body>
</html>
