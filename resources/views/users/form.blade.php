@extends('layouts.app')
@section('content')
@php($title = __($user->exists ? 'users.actions.edit_user' : 'users.actions.add'))
@php($breadcrumb = $title)
<div class="page-heading"><div><p class="eyebrow"><span class="eyebrow-line"></span> {{ __('users.eyebrow') }}</p><h1>{{ $title }}</h1><p>{{ __('users.form.introduction') }}</p></div><a class="btn btn-outline-secondary" href="{{ route('users.index') }}">{{ __('users.actions.back') }}</a></div>
<div class="card form-shell"><div class="card-body p-4 p-lg-5">
    <form method="post" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" data-loading-form>
        @csrf
        @if($user->exists) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-md-6"><label class="form-label" for="name">{{ __('users.fields.full_name') }} <span class="required-mark">*</span></label><input class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required></div>
            <div class="col-md-6"><label class="form-label" for="email">{{ __('users.fields.email') }} <span class="required-mark">*</span></label><input class="form-control" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required></div>
            <div class="col-md-4"><label class="form-label" for="role">{{ __('users.fields.role') }} <span class="required-mark">*</span></label><select class="form-select" id="role" name="role" required>@foreach(['admin','data_entry','viewer'] as $role)<option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ __('statuses.roles.'.$role) }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label" for="password">{{ __($user->exists ? 'users.fields.new_password' : 'users.fields.password') }} @if(!$user->exists)<span class="required-mark">*</span>@endif</label><input class="form-control" id="password" name="password" type="password" minlength="8" {{ $user->exists ? '' : 'required' }} autocomplete="new-password"><div class="form-text">{{ __('users.form.password_help') }}</div></div>
            <div class="col-md-4"><label class="form-label" for="is_active">{{ __('users.fields.account_status') }}</label><select class="form-select" id="is_active" name="is_active"><option value="1" @selected(old('is_active', $user->is_active ?? true))>{{ __('statuses.account.active') }}</option><option value="0" @selected(!old('is_active', $user->is_active ?? true))>{{ __('statuses.account.inactive') }}</option></select></div>
        </div>
        <div class="d-flex gap-2 mt-4"><button class="btn btn-primary" type="submit">{{ __('users.actions.save') }}</button><a class="btn btn-outline-secondary" href="{{ route('users.index') }}">{{ __('users.actions.cancel') }}</a></div>
    </form>
</div></div>
@endsection
