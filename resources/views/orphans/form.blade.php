@extends('layouts.app', ['title' => __($orphan->exists ? 'orphans.edit_title' : 'orphans.add_title'), 'breadcrumb' => __($orphan->exists ? 'orphans.edit_title' : 'orphans.add_title')])
@section('content')
<h1>{{ __($orphan->exists ? 'orphans.edit_title' : 'orphans.add_title') }}</h1>
<form method="post" action="{{ $orphan->exists ? '/orphans/'.$orphan->id : '/orphans' }}">@csrf @if($orphan->exists) @method('PUT') @endif
<div class="row g-3"><div class="col-md-6"><label class="form-label">{{ __('orphans.fields.family_id') }}</label><select class="form-select" name="family_id" required>@foreach($families as $family)<option value="{{ $family->id }}" @selected(old('family_id',$orphan->family_id)==$family->id)>{{ $family->case_number }} — {{ $family->head_of_household_name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">{{ __('orphans.fields.beneficiary_id') }}</label><select class="form-select" name="beneficiary_id" required>@foreach($beneficiaries as $beneficiary)<option value="{{ $beneficiary->id }}" @selected(old('beneficiary_id',$orphan->beneficiary_id)==$beneficiary->id)>{{ $beneficiary->beneficiary_number }} — {{ $beneficiary->full_name }}</option>@endforeach</select></div>
@foreach([
    'orphan_status' => ['orphan', \App\Http\Requests\OrphanRequest::ORPHAN_STATUSES],
    'father_status' => ['parent', \App\Http\Requests\OrphanRequest::PARENT_STATUSES],
    'mother_status' => ['parent', \App\Http\Requests\OrphanRequest::PARENT_STATUSES],
    'sponsorship_status' => ['sponsorship', \App\Http\Requests\OrphanRequest::SPONSORSHIP_STATUSES],
] as $field => [$group, $values])
<div class="col-md-4"><label class="form-label" for="{{ $field }}">{{ __('orphans.fields.'.$field) }}</label><select class="form-select" id="{{ $field }}" name="{{ $field }}" required><option value="" @selected(!old($field,$orphan->$field))>{{ __('orphans.fields.'.$field) }}</option>@foreach($values as $value)<option value="{{ $value }}" @selected(old($field,$orphan->$field)===$value)>{{ __('statuses.'.$group.'.'.$value) }}</option>@endforeach</select></div>
@endforeach
@foreach(['guardian_name','guardian_relationship','school_status'] as $field)<div class="col-md-4"><label class="form-label" for="{{ $field }}">{{ __('orphans.fields.'.$field) }}</label><input class="form-control" id="{{ $field }}" name="{{ $field }}" value="{{ old($field,$orphan->$field) }}"></div>@endforeach
<div class="col-12"><label class="form-label">{{ __('orphans.fields.notes') }}</label><textarea class="form-control" name="notes">{{ old('notes',$orphan->notes) }}</textarea></div></div>
@if($errors->any())<div class="alert alert-danger mt-3">{{ $errors->first() }}</div>@endif<button class="btn btn-primary mt-3">{{ __('orphans.save') }}</button> <a class="btn btn-link" href="/orphans">{{ __('orphans.cancel') }}</a></form>
@endsection
