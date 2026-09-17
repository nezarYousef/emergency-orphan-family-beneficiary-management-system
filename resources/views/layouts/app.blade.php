<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#102a43">
    <meta name="description" content="{{ __('common.description') }}">
    <title>{{ $title ?? __('common.app_title') }}</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap{{ app()->getLocale() === 'ar' ? '.rtl' : '' }}.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="app-shell" data-saving-label="{{ __('common.saving') }}">
    <a class="skip-link" href="#main-content">{{ __('common.skip_content') }}</a>
    @auth
        @php
            $user = auth()->user();
            $initial = mb_strtoupper(mb_substr(trim($user->name), 0, 1, 'UTF-8'), 'UTF-8');
            $role = __('common.roles.'.(in_array($user->role, ['admin', 'data_entry', 'viewer'], true) ? $user->role : 'unknown'));
            $routeParts = explode('.', request()->route()?->getName() ?? 'dashboard');
            $sectionKey = 'navigation.'.$routeParts[0];
            $actionKey = 'navigation.actions.'.($routeParts[1] ?? 'index');
            $fallbackBreadcrumb = \Illuminate\Support\Facades\Lang::has($sectionKey) ? __($sectionKey) : __('navigation.overview');
            if (($routeParts[1] ?? 'index') !== 'index' && \Illuminate\Support\Facades\Lang::has($actionKey)) {
                $fallbackBreadcrumb .= ' / '.__($actionKey);
            }
        @endphp
        <div class="app-frame">
            <aside class="app-sidebar" id="app-sidebar" aria-label="{{ __('navigation.primary') }}">
                <button class="sidebar-close" type="button" aria-label="{{ __('navigation.close') }}">×</button>
                <div class="sidebar-brand">
                    <a href="{{ route('dashboard') }}" class="brand-mark" aria-label="{{ __('navigation.dashboard_label') }}">
                        <span class="brand-icon" aria-hidden="true">+</span>
                        <span><strong>{{ __('common.app_name') }}</strong><small>{{ __('common.operations') }}</small></span>
                    </a>
                </div>
                <div class="sidebar-user">
                    <span class="avatar" aria-hidden="true">{{ $initial }}</span>
                    <span><strong><bdi>{{ $user->name }}</bdi></strong><small>{{ $role }}</small></span>
                </div>
                <nav class="sidebar-nav" aria-label="{{ __('navigation.main_menu') }}">
                    <span class="nav-label">{{ $user->isAdmin() ? __('navigation.administration') : ($user->isDataEntry() ? __('navigation.data_entry') : __('navigation.read_only_workspace')) }}</span>
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span aria-hidden="true">⌂</span> {{ __('navigation.overview') }}</a>
                    <a class="sidebar-link {{ request()->routeIs('families.*') ? 'active' : '' }}" href="{{ route('families.index') }}"><span aria-hidden="true">◌</span> {{ __('navigation.families') }}</a>
                    <a class="sidebar-link {{ request()->routeIs('beneficiaries.*') ? 'active' : '' }}" href="{{ route('beneficiaries.index') }}"><span aria-hidden="true">♧</span> {{ __('navigation.beneficiaries') }}</a>
                    <a class="sidebar-link {{ request()->routeIs('orphans.*') ? 'active' : '' }}" href="{{ route('orphans.index') }}"><span aria-hidden="true">♡</span> {{ __('navigation.orphans') }}</a>
                    <a class="sidebar-link {{ request()->routeIs('aid.*') ? 'active' : '' }}" href="{{ route('aid.index') }}"><span aria-hidden="true">▣</span> {{ __('navigation.aid') }}</a>
                    <span class="nav-label mt-4">{{ __('navigation.insights') }}</span>
                    <a class="sidebar-link {{ request()->routeIs('reports') ? 'active' : '' }}" href="{{ route('reports') }}"><span aria-hidden="true">◫</span> {{ __('navigation.reports') }}</a>
                    @can('manage-users')
                        <span class="nav-label mt-4">{{ __('navigation.administration') }}</span>
                        <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span aria-hidden="true">♙</span> {{ __('navigation.users') }}</a>
                    @endcan
                    @can('view-audit-logs')
                        <a class="sidebar-link {{ request()->routeIs('audit.index') ? 'active' : '' }}" href="{{ route('audit.index') }}"><span aria-hidden="true">⌁</span> {{ __('navigation.audit') }}</a>
                    @endcan
                </nav>
                <div class="sidebar-footer">
                    <a class="sidebar-link" href="{{ route('home') }}"><span class="directional-icon" aria-hidden="true">↗</span> {{ __('navigation.public_home') }}</a>
                    <form method="post" action="{{ route('logout') }}" data-loading-form data-loading-label="{{ __('common.signing_out') }}">
                        @csrf
                        <button class="sidebar-link sidebar-button" type="submit"><span class="directional-icon" aria-hidden="true">⇥</span> {{ __('auth.sign_out') }}</button>
                    </form>
                </div>
            </aside>
            <div class="app-main-wrap">
                <header class="app-topbar">
                    <button class="sidebar-toggle" type="button" aria-controls="app-sidebar" aria-expanded="false" aria-label="{{ __('navigation.open') }}" data-open-label="{{ __('navigation.open') }}" data-close-label="{{ __('navigation.close') }}">☰</button>
                    <div class="topbar-context"><span class="status-dot" aria-hidden="true"></span><span>{{ $user->isAdmin() ? __('navigation.administration') : ($user->isDataEntry() ? __('navigation.data_entry_workspace') : __('navigation.read_only_overview')) }}</span><span class="role-badge">{{ $role }}</span></div>
                    <div class="topbar-actions"><x-language-switcher /><span class="d-none d-sm-inline text-muted small">{{ now()->locale(app()->getLocale())->translatedFormat('D, d M Y') }}</span><span class="topbar-avatar" aria-hidden="true">{{ $initial }}</span></div>
                </header>
                <main id="main-content" class="app-main" tabindex="-1">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="status"><strong>{{ __('common.success') }}</strong> {{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('common.dismiss_message') }}"></button></div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert"><strong>{{ __('common.check_fields') }}</strong><ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <nav class="breadcrumb-wrap" aria-label="{{ __('navigation.breadcrumb') }}"><a href="{{ route('dashboard') }}">{{ __('navigation.workspace') }}</a><span aria-hidden="true">/</span><span>{{ $breadcrumb ?? $fallbackBreadcrumb }}</span></nav>
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
