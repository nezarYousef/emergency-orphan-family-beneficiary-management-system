@extends('layouts.app')
@section('content')
<h1>{{$beneficiary->beneficiary_number}}</h1><div class="card"><div class="card-body"><h2 class="h4">{{$beneficiary->full_name}}</h2><p>Family: <a href="/families/{{$beneficiary->family_id}}">{{$beneficiary->family?->case_number}}</a></p><p>Gender: {{$beneficiary->gender}} · Age: {{$beneficiary->date_of_birth?->age}} · Type: {{$beneficiary->beneficiary_type}}</p><a class="btn btn-outline-primary" href="/beneficiaries/{{$beneficiary->id}}/edit">Edit</a></div></div>
@endsection
