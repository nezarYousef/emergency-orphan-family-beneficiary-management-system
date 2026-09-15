@extends('layouts.app')
@section('content')
<h1>{{ $orphan->exists ? 'Edit' : 'Add' }} Orphan Record</h1>
<form method="post" action="{{ $orphan->exists ? '/orphans/'.$orphan->id : '/orphans' }}">@csrf @if($orphan->exists) @method('PUT') @endif
<div class="row g-3"><div class="col-md-6"><label class="form-label">Family</label><select class="form-select" name="family_id" required>@foreach($families as $family)<option value="{{ $family->id }}" @selected(old('family_id',$orphan->family_id)==$family->id)>{{ $family->case_number }} — {{ $family->head_of_household_name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Beneficiary</label><select class="form-select" name="beneficiary_id" required>@foreach($beneficiaries as $beneficiary)<option value="{{ $beneficiary->id }}" @selected(old('beneficiary_id',$orphan->beneficiary_id)==$beneficiary->id)>{{ $beneficiary->beneficiary_number }} — {{ $beneficiary->full_name }}</option>@endforeach</select></div>
@foreach(['orphan_status','father_status','mother_status','guardian_name','guardian_relationship','school_status','sponsorship_status'] as $field)<div class="col-md-4"><label class="form-label">{{ str($field)->replace('_',' ')->title() }}</label><input class="form-control" name="{{ $field }}" value="{{ old($field,$orphan->$field) }}" @required(in_array($field,['orphan_status','father_status','mother_status','sponsorship_status']))></div>@endforeach
<div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes">{{ old('notes',$orphan->notes) }}</textarea></div></div>
@if($errors->any())<div class="alert alert-danger mt-3">{{ $errors->first() }}</div>@endif<button class="btn btn-primary mt-3">Save</button> <a class="btn btn-link" href="/orphans">Cancel</a></form>
@endsection
