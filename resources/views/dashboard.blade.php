@extends('layouts.app')
@section('content')
@php($role = $user->isAdmin() ? 'admin' : ($user->isDataEntry() ? 'data_entry' : 'viewer'))
@php($title = __('dashboard.headings.'.$role))
@php($breadcrumb = __('dashboard.breadcrumb'))
<div class="page-heading">
    <div><p class="eyebrow"><span class="eyebrow-line"></span> {{ __('dashboard.workspace', ['role' => __('statuses.roles.'.$role)]) }}</p><h1>{{ $title }}</h1><p>{{ __($user->isViewer() ? 'dashboard.introduction.viewer' : 'dashboard.introduction.operations') }}</p></div>
    <div class="d-flex flex-wrap gap-2">
        @can('create-records')<a class="btn btn-primary" href="{{ route('families.create') }}">{{ __('dashboard.actions.add_family') }} <span aria-hidden="true">+</span></a>@endcan
        <a class="btn btn-outline-primary" href="{{ route('reports') }}">{{ __('dashboard.actions.view_reports') }} <span aria-hidden="true">→</span></a>
    </div>
</div>
<div class="alert {{ $user->isViewer() ? 'alert-secondary' : 'alert-info' }} d-flex align-items-start gap-2" role="note"><span aria-hidden="true">{{ $user->isViewer() ? '◉' : '✦' }}</span><div><strong>{{ __('dashboard.access.'.$role.'.heading') }}</strong> {{ __('dashboard.access.'.$role.'.description') }}</div></div>
@if($user->isDataEntry())
<section class="card mb-4" aria-labelledby="entry-summary-title"><div class="card-body"><div class="d-flex justify-content-between align-items-center gap-3 flex-wrap"><div><p class="eyebrow mb-1">{{ __('dashboard.today') }}</p><h2 id="entry-summary-title" class="h5 mb-0">{{ __('dashboard.entry_activity') }}</h2></div><span class="badge text-bg-info">{{ __('dashboard.entries_today', ['count' => number_format($myEntriesToday)]) }}</span></div></div></section>
@endif
<section aria-labelledby="key-metrics-title">
    <h2 id="key-metrics-title" class="visually-hidden">{{ __('dashboard.metrics.heading') }}</h2>
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3"><a class="stat-card d-block" href="{{ route('families.index') }}"><span class="stat-label">{{ __('dashboard.metrics.families') }}</span><span class="stat-value d-block">{{ number_format($families) }}</span><span class="stat-icon" aria-hidden="true">◌</span></a></div>
        <div class="col-sm-6 col-xl-3"><a class="stat-card d-block" href="{{ route('beneficiaries.index') }}"><span class="stat-label">{{ __('dashboard.metrics.beneficiaries') }}</span><span class="stat-value d-block">{{ number_format($beneficiaries) }}</span><span class="stat-icon" aria-hidden="true">♧</span></a></div>
        <div class="col-sm-6 col-xl-3"><a class="stat-card d-block" href="{{ route('orphans.index') }}"><span class="stat-label">{{ __('dashboard.metrics.orphans') }}</span><span class="stat-value d-block">{{ number_format($orphans) }}</span><span class="stat-icon" aria-hidden="true">♡</span></a></div>
        <div class="col-sm-6 col-xl-3"><a class="stat-card d-block" href="{{ route('families.index', ['provider_status' => 'no_provider']) }}"><span class="stat-label">{{ __('dashboard.metrics.without_provider') }}</span><span class="stat-value d-block">{{ number_format($withoutProvider) }}</span><span class="stat-icon" aria-hidden="true">!</span></a></div>
    </div>
</section>
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><p class="text-muted small mb-2">{{ __('dashboard.metrics.aid_this_month') }}</p><strong class="h3 mb-0">{{ number_format($aidThisMonth) }}</strong><p class="small text-muted mb-0 mt-2">{{ __('dashboard.metrics.recorded_distributions') }}</p></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><p class="text-muted small mb-2">{{ __('dashboard.metrics.sponsored_orphans') }}</p><strong class="h3 mb-0">{{ number_format($activeSponsoredOrphans) }}</strong><p class="small text-muted mb-0 mt-2">{{ __('dashboard.metrics.active_sponsorships') }}</p></div></div></div>
    <div class="col-xl-6"><div class="card h-100"><div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h6 mb-0">{{ __('dashboard.regions.heading') }}</h2><a class="small" href="{{ route('reports') }}">{{ __('dashboard.actions.full_report') }}</a></div>
        @forelse($familiesByRegion as $region)
            <div class="d-flex align-items-center gap-3 mb-2"><span class="small text-muted" style="width:110px">{{ \Illuminate\Support\Facades\Lang::has('statuses.governorate.'.$region->governorate) ? __('statuses.governorate.'.$region->governorate) : $region->governorate }}</span><div class="progress flex-grow-1" style="height:8px" aria-label="{{ __('dashboard.regions.family_share', ['region' => \Illuminate\Support\Facades\Lang::has('statuses.governorate.'.$region->governorate) ? __('statuses.governorate.'.$region->governorate) : $region->governorate]) }}"><div class="progress-bar bg-info" style="width:{{ $families ? min(100, ($region->total / $families) * 100) : 0 }}%"></div></div><strong class="small">{{ $region->total }}</strong></div>
        @empty
            <div class="empty-state py-3"><p class="mb-0">{{ __('dashboard.empty.regions') }}</p></div>
        @endforelse
    </div></div></div>
</div>
<div class="row g-4">
    <div class="col-lg-6"><div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center"><h2 class="h6 mb-0">{{ __('dashboard.recent.families') }}</h2><a class="small" href="{{ route('families.index') }}">{{ __('dashboard.actions.view_all') }}</a></div>
        <div class="list-group list-group-flush">
            @forelse($recentFamilies as $family)
                <a class="list-group-item list-group-item-action px-3 py-3" href="{{ route('families.show', $family) }}"><div class="d-flex justify-content-between gap-2"><strong>{{ $family->case_number }}</strong><span class="badge text-bg-light">{{ \Illuminate\Support\Facades\Lang::has('statuses.governorate.'.$family->governorate) ? __('statuses.governorate.'.$family->governorate) : $family->governorate }}</span></div><span class="small text-muted">{{ $family->head_of_household_name }}</span></a>
            @empty
                <div class="empty-state"><div class="empty-state-icon">◌</div><p class="mb-0">{{ __('dashboard.empty.families') }}</p></div>
            @endforelse
        </div>
    </div></div>
    <div class="col-lg-6"><div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center"><h2 class="h6 mb-0">{{ __('dashboard.recent.aid') }}</h2><a class="small" href="{{ route('aid.index') }}">{{ __('dashboard.actions.view_all') }}</a></div>
        <div class="list-group list-group-flush">
            @forelse($recentAid as $aid)
                <a class="list-group-item list-group-item-action px-3 py-3" href="{{ route('aid.show', $aid) }}"><div class="d-flex justify-content-between gap-2"><strong>{{ \Illuminate\Support\Facades\Lang::has('statuses.aid.'.$aid->aid_type) ? __('statuses.aid.'.$aid->aid_type) : $aid->aid_type }}</strong><span class="badge text-bg-light">{{ $aid->distribution_date?->locale(app()->getLocale())->translatedFormat('d M Y') }}</span></div><span class="small text-muted">{{ $aid->family?->case_number ?? __('dashboard.fallback.unlinked_case') }} · {{ $aid->reference_number ?? __('dashboard.fallback.no_reference') }}</span></a>
            @empty
                <div class="empty-state"><div class="empty-state-icon">▣</div><p class="mb-0">{{ __('dashboard.empty.aid') }}</p></div>
            @endforelse
        </div>
    </div></div>
</div>
@endsection
