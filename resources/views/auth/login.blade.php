<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#102a43">
    <title>Sign in | CareTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="auth-page">
    <a class="skip-link" href="#main-content">Skip to sign in</a>
    <main id="main-content" class="auth-shell">
        <section class="auth-story" aria-labelledby="auth-story-title">
            <a class="brand-mark" href="{{ route('home') }}" aria-label="Return to CareTrack home"><span class="brand-icon">+</span><span><strong>CareTrack</strong><small>Humanitarian operations</small></span></a>
            <div><p class="eyebrow text-white-50"><span class="eyebrow-line"></span> Secure workspace</p><h1 id="auth-story-title">Keep every case moving with care.</h1><p>One shared picture for the people coordinating family support, beneficiary services, and aid distributions.</p></div>
            <p class="mb-0 small text-white-50">Demo environment · Fictional data only</p>
        </section>
        <section class="auth-panel" aria-labelledby="sign-in-title">
            <div class="auth-card">
                <a class="d-inline-block mb-3 text-decoration-none" href="{{ route('home') }}">← Back to public home</a>
                <h2 id="sign-in-title">Welcome back</h2>
                <p class="text-muted mb-4">Sign in to your operations workspace.</p>
                @if ($errors->any())<div class="alert alert-danger" role="alert"><strong>Unable to sign in.</strong> {{ $errors->first() }}</div>@endif
                <form method="post" action="{{ route('login') }}" data-no-loading>
                    @csrf
                    <div class="mb-3"><label class="form-label" for="email">Email <span class="required-mark" aria-hidden="true">*</span></label><input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required aria-describedby="email-help"><div id="email-help" class="form-text">Use your assigned workspace email.</div></div>
                    <div class="mb-3"><label class="form-label" for="password">Password <span class="required-mark" aria-hidden="true">*</span></label><input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required></div>
                    <button class="btn btn-primary btn-lg w-100" type="submit">Sign in <span aria-hidden="true">→</span></button>
                </form>
                <div class="demo-note mt-4" role="note"><strong>Demo access</strong><br>admin@example.com · password<br>dataentry@example.com · password<br>viewer@example.com · password</div>
            </div>
        </section>
    </main>
</body>
</html>
