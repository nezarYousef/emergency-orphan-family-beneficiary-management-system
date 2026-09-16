@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between"><h1>{{ $family->case_number }}</h1>@can('edit-records')<a class="btn btn-outline-primary" href="{{ route('families.edit', $family) }}">Edit</a>@endcan</div>
<div class="card mb-4"><div class="card-body"><h2 class="h5">{{ $family->head_of_household_name }}</h2><p>{{ $family->governorate }} · {{ $family->area }} · {{ $family->phone }}</p><p>Family size: {{ $family->family_size }} | Provider: {{ $family->provider_status }} | Vulnerability: {{ $family->vulnerability_status }}</p></div></div>
<h2 class="h4">Beneficiaries</h2><div class="card p-3 mb-4">@forelse($family->beneficiaries as $b)<div><a href="{{ route('beneficiaries.show', $b) }}">{{ $b->beneficiary_number }}</a> — {{ $b->full_name }}</div>@empty<em>No beneficiaries.</em>@endforelse</div>
<h2 class="h4">Orphans</h2><div class="card p-3 mb-4">@forelse($family->orphans as $o)<div><a href="{{ route('orphans.show', $o) }}">{{ $o->orphan_number }}</a> — {{ $o->beneficiary?->full_name }}</div>@empty<em>No orphans.</em>@endforelse</div>
<h2 class="h4">Aid history</h2><div class="card p-3">@forelse($family->aidDistributions as $a)<div><a href="{{ route('aid.show', $a) }}">{{ $a->distribution_date?->format('Y-m-d') }}</a> — {{ $a->aid_type }} — {{ $a->amount ?? '—' }}</div>@empty<em>No aid distributions.</em>@endforelse</div>
@endsection
