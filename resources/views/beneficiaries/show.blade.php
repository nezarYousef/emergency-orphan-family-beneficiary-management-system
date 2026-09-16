@extends('layouts.app')
@section('content')
<h1>{{ $beneficiary->beneficiary_number }}</h1><div class="card"><div class="card-body"><h2 class="h4">{{ $beneficiary->full_name }}</h2><p>Family: <a href="{{ route('families.show', $beneficiary->family_id) }}">{{ $beneficiary->family?->case_number }}</a></p><p>Gender: {{ $beneficiary->gender }} · Age: {{ $beneficiary->date_of_birth?->age }} · Type: {{ $beneficiary->beneficiary_type }}</p>@can('edit-records')<a class="btn btn-outline-primary" href="{{ route('beneficiaries.edit', $beneficiary) }}">Edit</a>@endcan</div></div>
@endsection
