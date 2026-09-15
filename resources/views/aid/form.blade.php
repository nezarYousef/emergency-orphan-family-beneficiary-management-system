@extends('layouts.app')
@section('content')
<h1>{{ $distribution->exists ? 'Edit' : 'Record' }} Aid Distribution</h1>
<form method="post" action="{{ $distribution->exists ? '/aid-distributions/'.$distribution->id : '/aid-distributions' }}">@csrf @if($distribution->exists) @method('PUT') @endif
<div class="row g-3"><div class="col-md-6"><label class="form-label">Family</label><select class="form-select" name="family_id" required>@foreach($families as $family)<option value="{{ $family->id }}" @selected(old('family_id',$distribution->family_id)==$family->id)>{{ $family->case_number }} — {{ $family->head_of_household_name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Beneficiary (optional)</label><select class="form-select" name="beneficiary_id"><option value="">Family-level assistance</option>@foreach($beneficiaries as $beneficiary)<option value="{{ $beneficiary->id }}" @selected(old('beneficiary_id',$distribution->beneficiary_id)==$beneficiary->id)>{{ $beneficiary->beneficiary_number }} — {{ $beneficiary->full_name }}</option>@endforeach</select></div>
@foreach(['aid_type','distribution_date','quantity','amount','currency','provider_organization','reference_number'] as $field)<div class="col-md-4"><label class="form-label">{{ str($field)->replace('_',' ')->title() }}</label><input class="form-control" name="{{ $field }}" type="{{ $field==='distribution_date'?'date':'text' }}" value="{{ old($field,$distribution->$field) }}" @required(in_array($field,['aid_type','distribution_date']))></div>@endforeach
<div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes">{{ old('notes',$distribution->notes) }}</textarea></div></div>
@if($errors->any())<div class="alert alert-danger mt-3">{{ $errors->first() }}</div>@endif<button class="btn btn-primary mt-3">Save</button> <a class="btn btn-link" href="/aid-distributions">Cancel</a></form>
@endsection
