@extends('layouts.app')
@section('content')
<h1>Audit Logs</h1><form class="row g-2 my-3"><div class="col-md-8"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search action, model, description"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button></div></form>
<div class="table-responsive card"><table class="table table-striped mb-0"><thead><tr><th>Time</th><th>User</th><th>Action</th><th>Model</th><th>Description</th><th>IP</th></tr></thead><tbody>@forelse($logs as $log)<tr><td>{{ $log->created_at?->format('Y-m-d H:i') }}</td><td>{{ $log->user?->email ?? 'System' }}</td><td>{{ $log->action }}</td><td>{{ $log->model_type }} #{{ $log->model_id }}</td><td>{{ $log->description }}</td><td>{{ $log->ip_address }}</td></tr>@empty<tr><td colspan="6" class="p-4">No audit entries found.</td></tr>@endforelse</tbody></table></div><div class="mt-3">{{ $logs->links() }}</div>
@endsection
