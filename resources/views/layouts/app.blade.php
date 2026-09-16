<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#102a43">
    <meta name="description" content="Secure humanitarian case management for families, beneficiaries, orphans, and aid distributions.">
    <title>{{ $title ?? 'Humanitarian Case Management' }}</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="app-shell">
    <a class="skip-link" href="#main-content">Skip to main content</a>
    @auth
        @php($user = auth()->user())
        <div class="app-frame">
            <aside class="app-sidebar" id="app-sidebar" aria-label="Primary navigation">
                <div class="sidebar-brand">
                    <a href="{{ route('dashboard') }}" class="brand-mark" aria-label="Go to dashboard">
                        <span class="brand-icon" aria-hidden="true">+</span>
                        <span><strong>CareTrack</strong><small>Humanitarian operations</small></span>
                    </a>
                </div>
                <div class="sidebar-user">
                    <span class="avatar" aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    <span><strong>{{ $user->name }}</strong><small>{{ str($user->role)->replace('_', ' ')->title() }}</small></span>
                </div>
                <nav class="sidebar-nav" aria-label="Main menu">
                    <span class="nav-label">{{ $user->isAdmin() ? 'Administration' : ($user->isDataEntry() ? 'Data entry' : 'Read-only workspace') }}</span>
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span aria-hidden="true">⌂</span> Overview</a>
                    <a class="sidebar-link {{ request()->routeIs('families.*') ? 'active' : '' }}" href="{{ route('families.index') }}"><span aria-hidden="true">◌</span> Families</a>
                    <a class="sidebar-link {{ request()->routeIs('beneficiaries.*') ? 'active' : '' }}" href="{{ route('beneficiaries.index') }}"><span aria-hidden="true">♧</span> Beneficiaries</a>
                    <a class="sidebar-link {{ request()->routeIs('orphans.*') ? 'active' : '' }}" href="{{ route('orphans.index') }}"><span aria-hidden="true">♡</span> Orphans</a>
                    <a class="sidebar-link {{ request()->routeIs('aid.*') ? 'active' : '' }}" href="{{ route('aid.index') }}"><span aria-hidden="true">▣</span> Aid distributions</a>
                    <span class="nav-label mt-4">Insights</span>
                    <a class="sidebar-link {{ request()->routeIs('reports') ? 'active' : '' }}" href="{{ route('reports') }}"><span aria-hidden="true">◫</span> Reports</a>
                    @can('manage-users')
                        <span class="nav-label mt-4">Administration</span>
                        <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span aria-hidden="true">♙</span> Users</a>
                    @endcan
                    @can('view-audit-logs')
                        <a class="sidebar-link {{ request()->routeIs('audit.index') ? 'active' : '' }}" href="{{ route('audit.index') }}"><span aria-hidden="true">⌁</span> Audit logs</a>
                    @endcan
                </nav>
                <div class="sidebar-footer">
                    <a class="sidebar-link" href="{{ route('home') }}"><span aria-hidden="true">↗</span> Public home</a>
                    <form method="post" action="{{ route('logout') }}" data-loading-form>
                        @csrf
                        <button class="sidebar-link sidebar-button" type="submit"><span aria-hidden="true">⇥</span> Sign out</button>
                    </form>
                </div>
            </aside>
            <div class="app-main-wrap">
                <header class="app-topbar">
                    <button class="sidebar-toggle" type="button" aria-controls="app-sidebar" aria-expanded="false" aria-label="Open navigation">☰</button>
                    <div class="topbar-context"><span class="status-dot" aria-hidden="true"></span><span>{{ $user->isAdmin() ? 'Administration' : ($user->isDataEntry() ? 'Data entry workspace' : 'Read-only overview') }}</span><span class="role-badge">{{ str($user->role)->replace('_', ' ')->upper() }}</span></div>
                    <div class="topbar-actions"><span class="d-none d-sm-inline text-muted small">{{ now()->format('D, d M Y') }}</span><span class="topbar-avatar" aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</span></div>
                </header>
                <main id="main-content" class="app-main" tabindex="-1">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="status"><strong>Success.</strong> {{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss message"></button></div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert"><strong>Check the highlighted fields.</strong><ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <nav class="breadcrumb-wrap" aria-label="Breadcrumb"><a href="{{ route('dashboard') }}">Workspace</a><span aria-hidden="true">/</span><span>{{ $breadcrumb ?? (request()->route()?->getName() ? str(request()->route()->getName())->afterLast('.')->replace('_', ' ')->title() : 'Overview') }}</span></nav>
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
