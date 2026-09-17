@extends('layouts.app')
@section('content')
@php($title = __('audit.title'))
@php($breadcrumb = $title)
<div class="page-heading"><div><p class="eyebrow"><span class="eyebrow-line"></span> {{ __('audit.eyebrow') }}</p><h1>{{ $title }}</h1><p>{{ __('audit.introduction') }}</p></div></div>
<form class="filter-panel row g-3 mb-4" method="get" aria-label="{{ __('audit.filters.label') }}"><div class="col-lg-8"><label class="form-label" for="audit-search">{{ __('audit.search') }}</label><input class="form-control" id="audit-search" name="search" value="{{ request('search') }}" placeholder="{{ __('audit.filters.placeholder') }}"></div><div class="col-md-4 d-flex align-items-end gap-2"><button class="btn btn-primary flex-grow-1" type="submit">{{ __('audit.search') }}</button><a class="btn btn-outline-secondary" href="{{ route('audit.index') }}" aria-label="{{ __('audit.filters.clear') }}">×</a></div></form>
<div class="table-card card"><div class="table-responsive"><table class="table">
    <caption class="visually-hidden">{{ __('audit.caption') }}</caption>
    <thead><tr><th scope="col">{{ __('audit.fields.time') }}</th><th scope="col">{{ __('audit.fields.user') }}</th><th scope="col">{{ __('audit.fields.action') }}</th><th scope="col">{{ __('audit.fields.model') }}</th><th scope="col">{{ __('audit.fields.description') }}</th><th scope="col">{{ __('audit.fields.ip') }}</th></tr></thead>
    <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                <td>{{ $log->user?->email ?? __('audit.system') }}</td>
                <td><span class="badge text-bg-light">{{ \Illuminate\Support\Facades\Lang::has('statuses.audit_action.'.$log->action) ? __('statuses.audit_action.'.$log->action) : $log->action }}</span></td>
                <td>{{ \Illuminate\Support\Facades\Lang::has('statuses.model_type.'.$log->model_type) ? __('statuses.model_type.'.$log->model_type) : $log->model_type }} #{{ $log->model_id }}</td>
                <td>{{ filled($log->description) && \Illuminate\Support\Facades\Lang::has('audit.descriptions.'.$log->description) ? __('audit.descriptions.'.$log->description) : $log->description }}</td>
                <td>{{ $log->ip_address }}</td>
            </tr>
        @empty
            <tr><td colspan="6"><div class="empty-state"><div class="empty-state-icon">⌁</div><h2 class="h6">{{ __('audit.empty.heading') }}</h2><p class="mb-0">{{ __('audit.empty.description') }}</p></div></td></tr>
        @endforelse
    </tbody>
</table></div></div>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection
