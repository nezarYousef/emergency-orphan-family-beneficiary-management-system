@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center"><h1>Orphans</h1><a class="btn btn-primary" href="/orphans/create">Add Orphan Record</a></div>
<form class="row g-2 my-3"><div class="col-md-8"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search orphan number, beneficiary, guardian"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button></div></form>
<div class="table-responsive card"><table class="table table-striped mb-0"><thead><tr><th>Number</th><th>Beneficiary</th><th>Family</th><th>Status</th><th>Sponsorship</th></tr></thead><tbody>
@forelse($orphans as $orphan)<tr><td><a href="/orphans/{{ $orphan->id }}">{{ $orphan->orphan_number }}</a></td><td>{{ $orphan->beneficiary?->full_name }}</td><td>{{ $orphan->family?->case_number }}</td><td>{{ $orphan->orphan_status }}</td><td>{{ $orphan->sponsorship_status }}</td></tr>@empty<tr><td colspan="5" class="p-4">No orphan records found.</td></tr>@endforelse
</tbody></table></div><div class="mt-3">{{ $orphans->links() }}</div>
@endsection
