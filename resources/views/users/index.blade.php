@extends('layouts.app')
@section('content')
@php($title = __('users.title'))
@php($breadcrumb = $title)
<div class="page-heading"><div><p class="eyebrow"><span class="eyebrow-line"></span> {{ __('users.eyebrow') }}</p><h1>{{ $title }}</h1><p>{{ __('users.introduction') }}</p></div><a class="btn btn-primary" href="{{ route('users.create') }}">{{ __('users.actions.add') }} <span aria-hidden="true">+</span></a></div>
<form class="filter-panel row g-3 mb-4" method="get" aria-label="{{ __('users.filters.label') }}"><div class="col-lg-8"><label class="form-label" for="user-search">{{ __('users.actions.search') }}</label><input class="form-control" id="user-search" name="search" value="{{ request('search') }}" placeholder="{{ __('users.filters.placeholder') }}"></div><div class="col-md-4 d-flex align-items-end gap-2"><button class="btn btn-primary flex-grow-1" type="submit">{{ __('users.actions.search') }}</button><a class="btn btn-outline-secondary" href="{{ route('users.index') }}" aria-label="{{ __('users.filters.clear') }}">×</a></div></form>
<div class="table-card card"><div class="table-responsive"><table class="table">
    <caption class="visually-hidden">{{ __('users.caption') }}</caption>
    <thead><tr><th scope="col">{{ __('users.fields.name') }}</th><th scope="col">{{ __('users.fields.email') }}</th><th scope="col">{{ __('users.fields.role') }}</th><th scope="col">{{ __('users.fields.status') }}</th><th scope="col"><span class="visually-hidden">{{ __('users.fields.actions') }}</span></th></tr></thead>
    <tbody>
        @forelse($users as $user)
            <tr><td><strong>{{ $user->name }}</strong></td><td>{{ $user->email }}</td><td><span class="badge text-bg-light">{{ __('statuses.roles.'.$user->role) }}</span></td><td><span class="badge {{ $user->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ __($user->is_active ? 'statuses.account.active' : 'statuses.account.inactive') }}</span></td><td><a href="{{ route('users.edit', $user) }}">{{ __('users.actions.edit') }}</a>@if($user->is_active && $user->id !== auth()->id())<form class="d-inline ms-2" method="post" action="{{ route('users.destroy', $user) }}" data-no-loading>@csrf @method('DELETE')<button class="btn btn-link btn-sm p-0 text-danger" type="submit" data-confirm="{{ __('users.confirm_deactivate') }}" onclick="return confirm(this.dataset.confirm)">{{ __('users.actions.deactivate') }}</button></form>@endif</td></tr>
        @empty
            <tr><td colspan="5"><div class="empty-state"><div class="empty-state-icon">♙</div><h2 class="h6">{{ __('users.empty.heading') }}</h2><p class="mb-0">{{ __('users.empty.description') }}</p></div></td></tr>
        @endforelse
    </tbody>
</table></div></div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
