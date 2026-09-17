@extends('layouts.app', ['title' => __($beneficiary->exists ? 'beneficiaries.edit_title' : 'beneficiaries.add_title'), 'breadcrumb' => __($beneficiary->exists ? 'beneficiaries.edit_title' : 'beneficiaries.add_title')])
@section('content')
<h1>{{ __($beneficiary->exists ? 'beneficiaries.edit_title' : 'beneficiaries.add_title') }}</h1>
<form method="post" action="{{$beneficiary->exists?'/beneficiaries/'.$beneficiary->id:'/beneficiaries'}}">@csrf @if($beneficiary->exists)@method('PUT')@endif
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">{{ __('beneficiaries.fields.family_id') }}</label><select class="form-select" name="family_id" required>@foreach($families as $f)<option value="{{$f->id}}" @selected(old('family_id',$beneficiary->family_id)==$f->id)>{{$f->case_number}} — {{$f->head_of_household_name}}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">{{ __('beneficiaries.fields.full_name') }}</label><input class="form-control" name="full_name" value="{{old('full_name',$beneficiary->full_name)}}" required></div>
    <div class="col-md-4"><label class="form-label">{{ __('beneficiaries.fields.gender') }}</label><select class="form-select" name="gender">@foreach(['female','male'] as $v)<option value="{{ $v }}" @selected(old('gender',$beneficiary->gender)===$v)>{{ __('statuses.gender.'.$v) }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">{{ __('beneficiaries.fields.date_of_birth') }}</label><input class="form-control" type="date" name="date_of_birth" value="{{old('date_of_birth',$beneficiary->date_of_birth?->format('Y-m-d'))}}" required></div>
    <div class="col-md-4"><label class="form-label">{{ __('beneficiaries.fields.beneficiary_type') }}</label><select class="form-select" name="beneficiary_type">@foreach(['child','adult','elderly','person_with_disability','caregiver','other'] as $v)<option value="{{ $v }}" @selected(old('beneficiary_type',$beneficiary->beneficiary_type)===$v)>{{ __('statuses.beneficiary_type.'.$v) }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">{{ __('beneficiaries.fields.relationship_to_head') }}</label><input class="form-control" name="relationship_to_head" value="{{old('relationship_to_head',$beneficiary->relationship_to_head)}}" required></div>
    <div class="col-md-6"><label class="form-label">{{ __('beneficiaries.fields.national_id') }}</label><input class="form-control" name="national_id" value="{{old('national_id',$beneficiary->national_id)}}"></div>
    <div class="col-12"><label><input type="hidden" name="disability_status" value="0"><input type="checkbox" name="disability_status" value="1" @checked(old('disability_status',$beneficiary->disability_status))> {{ __('beneficiaries.fields.disability_status') }}</label></div>
</div>
@if($errors->any())<div class="alert alert-danger mt-3">{{$errors->first()}}</div>@endif<button class="btn btn-primary mt-3">{{ __('beneficiaries.save') }}</button>
</form>
@endsection
