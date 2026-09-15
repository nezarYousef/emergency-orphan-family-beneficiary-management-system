@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between"><h1>{{ $orphan->orphan_number }}</h1><a class="btn btn-outline-primary" href="/orphans/{{ $orphan->id }}/edit">Edit</a></div>
<div class="card"><div class="card-body"><dl class="row mb-0"><dt class="col-sm-3">Beneficiary</dt><dd class="col-sm-9">{{ $orphan->beneficiary?->full_name }}</dd><dt class="col-sm-3">Family</dt><dd class="col-sm-9">{{ $orphan->family?->case_number }}</dd><dt class="col-sm-3">Orphan status</dt><dd class="col-sm-9">{{ $orphan->orphan_status }}</dd><dt class="col-sm-3">Sponsorship</dt><dd class="col-sm-9">{{ $orphan->sponsorship_status }}</dd><dt class="col-sm-3">Guardian</dt><dd class="col-sm-9">{{ $orphan->guardian_name }} ({{ $orphan->guardian_relationship }})</dd></dl></div></div>
@endsection
