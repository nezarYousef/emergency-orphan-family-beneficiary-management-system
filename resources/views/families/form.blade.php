@extends('layouts.app', ['title' => __($family->exists ? 'families.edit_title' : 'families.add_title'), 'breadcrumb' => __($family->exists ? 'families.edit_title' : 'families.add_title')])
@section('content')
<h1>{{ __($family->exists ? 'families.edit_title' : 'families.add_title') }}</h1>
<form method="post" action="{{$family->exists?'/families/'.$family->id:'/families'}}">@csrf @if($family->exists)@method('PUT')@endif
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">{{ __('families.fields.head_of_household_name') }}</label><input class="form-control" name="head_of_household_name" value="{{old('head_of_household_name',$family->head_of_household_name)}}" required></div>
    <div class="col-md-6"><label class="form-label">{{ __('families.fields.phone') }}</label><input class="form-control" name="phone" value="{{old('phone',$family->phone)}}" required></div>
    @foreach(['national_id','area','address','notes'] as $f)
        <div class="col-md-6"><label class="form-label">{{ __('families.fields.'.$f) }}</label><input class="form-control" name="{{$f}}" value="{{old($f,$family->$f)}}"></div>
    @endforeach
    <div class="col-md-4"><label class="form-label">{{ __('families.fields.governorate') }}</label><select class="form-select" name="governorate">@foreach(['North Gaza','Gaza','Deir al-Balah','Khan Younis','Rafah'] as $v)<option value="{{ $v }}" @selected(old('governorate',$family->governorate)===$v)>{{ __('statuses.governorate.'.$v) }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">{{ __('families.fields.family_size') }}</label><input class="form-control" type="number" min="1" name="family_size" value="{{old('family_size',$family->family_size?:1)}}"></div>
    <div class="col-md-4"><label class="form-label">{{ __('families.fields.provider_status') }}</label><select class="form-select" name="provider_status">@foreach(['has_provider','no_provider','deceased_provider','missing_provider','disabled_provider'] as $v)<option value="{{ $v }}" @selected(old('provider_status',$family->provider_status)===$v)>{{ __('statuses.provider.'.$v) }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">{{ __('families.fields.vulnerability_status') }}</label><select class="form-select" name="vulnerability_status">@foreach(['low','medium','high','critical'] as $v)<option value="{{ $v }}" @selected(old('vulnerability_status',$family->vulnerability_status)===$v)>{{ __('statuses.vulnerability.'.$v) }}</option>@endforeach</select></div>
</div>
@if($errors->any())<div class="alert alert-danger mt-3">{{$errors->first()}}</div>@endif
<button class="btn btn-primary mt-3">{{ __('families.save') }}</button> <a class="btn btn-link" href="/families">{{ __('families.cancel') }}</a>
</form>
@endsection
