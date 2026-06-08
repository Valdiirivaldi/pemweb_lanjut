@extends('layouts.dashboard')

@section('title', 'Manage Users - Eduria')
@section('page-title', 'Manage Users')

@section('content')
    <div class="content-card shadow-sm">
        <div class="card-header">
            <span><i class="fas fa-users me-2"></i>Student & Tentor Accounts</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary" style="border-radius: 10px; font-weight: 600;">
                <i class="fas fa-plus me-1"></i>Add Account
            </a>
        </div>
        <div class="card-body">
            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
                <div class="col-md-5">
                    <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search by name or email..."
                               value="{{ request('search') }}" style="height: 40px;">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select" style="height: 40px; border-radius: 10px;">
                        <option value="">All Roles</option>
                        <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Student</option>
                        <option value="tentor" {{ request('role') === 'tentor' ? 'selected' : '' }}>Tentor</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; height: 40px; font-weight: 600;">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" style="border-radius: 10px; height: 40px; font-weight: 600;">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>

            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Unique ID</th>
                                <th>Registration Date</th>
                                <th style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $u)
                                <tr>
                                    <td class="text-muted">{{ $users->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $u->name }}</td>
                                    <td class="text-muted">{{ $u->email }}</td>
                                    <td>
                                        <span class="badge-role {{ $u->role }}">{{ ucfirst($u->role) }}</span>
                                    </td>
                                    <td>
                                        @if ($u->unique_id)
                                            <span style="font-family:monospace;font-size:0.85rem;color:#1e3c72;font-weight:600;">
                                                {{ $u->unique_id }}
                                            </span>
                                        @else
                                            <span class="text-muted" style="font-size:0.8rem;">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $u->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $u->id) }}"
                                           class="btn btn-sm btn-warning me-1"
                                           style="border-radius: 8px; font-weight: 600; color: #fff;"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $u->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete account {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    style="border-radius: 8px; font-weight: 600;"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center py-3">
                    {{ $users->withQueryString()->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h6>No users found</h6>
                    <p>@if(request('search') || request('role')) No users match your filter criteria. Try a different search. @else No students or tentors registered yet. Click "Add Account" to add one. @endif</p>
                    @if(!request('search') && !request('role'))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-2" style="border-radius: 10px;">
                            <i class="fas fa-plus me-1"></i>Add Account
                        </a>
                    @else
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mt-2" style="border-radius: 10px;">
                            <i class="fas fa-redo me-1"></i>Reset Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
