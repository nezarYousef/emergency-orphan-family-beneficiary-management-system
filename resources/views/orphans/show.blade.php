@extends('layouts.app', ['title' => __('orphans.show_title'), 'breadcrumb' => __('orphans.show_title')])
@section('content')
<div class="d-flex justify-content-between"><h1>{{ $orphan->orphan_number }}</h1>@can('edit-records')<a class="btn btn-outline-primary" href="/orphans/{{ $orphan->id }}/edit">{{ __('orphans.edit') }}</a>@endcan</div>
<div class="card"><div class="card-body"><dl class="row mb-0">
    <dt class="col-sm-3">{{ __('orphans.fields.beneficiary_id') }}</dt><dd class="col-sm-9">{{ $orphan->beneficiary?->full_name }}</dd>
    <dt class="col-sm-3">{{ __('orphans.fields.family_id') }}</dt><dd class="col-sm-9">{{ $orphan->family?->case_number }}</dd>
    <dt class="col-sm-3">{{ __('orphans.fields.orphan_status') }}</dt><dd class="col-sm-9">{{ \Illuminate\Support\Facades\Lang::has('statuses.orphan.'.$orphan->orphan_status) ? __('statuses.orphan.'.$orphan->orphan_status) : $orphan->orphan_status }}</dd>
    <dt class="col-sm-3">{{ __('orphans.fields.sponsorship_status') }}</dt><dd class="col-sm-9">{{ \Illuminate\Support\Facades\Lang::has('statuses.sponsorship.'.$orphan->sponsorship_status) ? __('statuses.sponsorship.'.$orphan->sponsorship_status) : $orphan->sponsorship_status }}</dd>
    <dt class="col-sm-3">{{ __('orphans.fields.guardian_name') }}</dt><dd class="col-sm-9">{{ $orphan->guardian_name }} ({{ filled($orphan->guardian_relationship) && \Illuminate\Support\Facades\Lang::has('statuses.relationship.'.$orphan->guardian_relationship) ? __('statuses.relationship.'.$orphan->guardian_relationship) : $orphan->guardian_relationship }})</dd>
</dl></div></div>
@endsection
