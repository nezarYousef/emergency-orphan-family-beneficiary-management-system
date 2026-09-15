@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between"><h1>Aid Distribution</h1><a class="btn btn-outline-primary" href="/aid-distributions/{{ $distribution->id }}/edit">Edit</a></div>
<div class="card"><div class="card-body"><dl class="row mb-0"><dt class="col-sm-3">Date</dt><dd class="col-sm-9">{{ $distribution->distribution_date?->format('Y-m-d') }}</dd><dt class="col-sm-3">Family</dt><dd class="col-sm-9">{{ $distribution->family?->case_number }}</dd><dt class="col-sm-3">Aid type</dt><dd class="col-sm-9">{{ $distribution->aid_type }}</dd><dt class="col-sm-3">Amount</dt><dd class="col-sm-9">{{ $distribution->amount }} {{ $distribution->currency }}</dd><dt class="col-sm-3">Reference</dt><dd class="col-sm-9">{{ $distribution->reference_number }}</dd></dl></div></div>
@endsection
