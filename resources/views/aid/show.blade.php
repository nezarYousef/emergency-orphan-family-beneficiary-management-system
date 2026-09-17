@extends('layouts.app', ['title' => __('aid.show_title'), 'breadcrumb' => __('aid.show_title')])
@section('content')
<div class="d-flex justify-content-between"><h1>{{ __('aid.show_title') }}</h1>@can('edit-records')<a class="btn btn-outline-primary" href="/aid-distributions/{{ $distribution->id }}/edit">{{ __('aid.edit') }}</a>@endcan</div>
<div class="card"><div class="card-body"><dl class="row mb-0">
    <dt class="col-sm-3">{{ __('aid.fields.distribution_date') }}</dt><dd class="col-sm-9">{{ $distribution->distribution_date?->format('Y-m-d') }}</dd>
    <dt class="col-sm-3">{{ __('aid.fields.family_id') }}</dt><dd class="col-sm-9">{{ $distribution->family?->case_number }}</dd>
    <dt class="col-sm-3">{{ __('aid.fields.aid_type') }}</dt><dd class="col-sm-9">{{ \Illuminate\Support\Facades\Lang::has('statuses.aid.'.$distribution->aid_type) ? __('statuses.aid.'.$distribution->aid_type) : $distribution->aid_type }}</dd>
    <dt class="col-sm-3">{{ __('aid.fields.amount') }}</dt><dd class="col-sm-9">{{ $distribution->amount }} {{ $distribution->currency }}</dd>
    <dt class="col-sm-3">{{ __('aid.fields.reference_number') }}</dt><dd class="col-sm-9">{{ $distribution->reference_number }}</dd>
</dl></div></div>
@endsection
