@extends('layouts.app')
@section('content')
@php($title = __('reports.title'))
@php($breadcrumb = $title)
<div class="page-heading"><div><p class="eyebrow"><span class="eyebrow-line"></span> {{ __('reports.eyebrow') }}</p><h1>{{ $title }}</h1><p>{{ __('reports.introduction') }}</p></div>@can('export-data')<a class="btn btn-outline-primary" href="{{ route('export', ['type' => 'families']) }}">{{ __('reports.actions.export') }} <span aria-hidden="true">↓</span></a>@endcan</div>
<div class="row g-4">
    @foreach([['governorates', $governorates, 'governorate', 'governorate'], ['sponsorship', $sponsorship, 'sponsorship_status', 'sponsorship'], ['vulnerability', $vulnerability, 'vulnerability_status', 'vulnerability'], ['aid', $aid, 'aid_type', 'aid']] as [$heading, $rows, $label, $statusGroup])
        <div class="col-lg-6"><section class="card h-100" aria-labelledby="report-{{ $loop->index }}">
            <div class="card-header d-flex justify-content-between align-items-center"><h2 id="report-{{ $loop->index }}" class="h6 mb-0">{{ __('reports.headings.'.$heading) }}</h2><span class="badge text-bg-light">{{ __('reports.groups', ['count' => $rows->count()]) }}</span></div>
            <div class="card-body">
                @forelse($rows as $row)
                    <div class="d-flex justify-content-between gap-3 align-items-center py-2 border-bottom"><span>{{ $row->{$label} === null ? __('reports.unknown') : ($statusGroup && \Illuminate\Support\Facades\Lang::has('statuses.'.$statusGroup.'.'.$row->{$label}) ? __('statuses.'.$statusGroup.'.'.$row->{$label}) : $row->{$label}) }}</span><strong>{{ $row->total ?? $row->families ?? 0 }}</strong></div>
                @empty
                    <div class="empty-state py-4"><div class="empty-state-icon">◫</div><p class="mb-0">{{ __('reports.empty') }}</p></div>
                @endforelse
            </div>
        </section></div>
    @endforeach
</div>
@endsection
