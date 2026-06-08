@extends('layouts.dashboard')

@section('title', 'Manage Users - Eduria')
@section('page-title', 'Manage Users')
@section('breadcrumb')
    <a href="{{ route('home') }}">Home</a>
    <i data-lucide="chevron-right"></i>
    <a href="{{ route('admin.dashboard') }}">Admin</a>
    <i data-lucide="chevron-right"></i>
    <span class="current">Manage Users</span>
@endsection

@section('content')
    <div class="content-card">
        <div class="content-card-header">
            <span><i data-lucide="users" style="margin-right:8px;"></i>Student & Tentor Accounts</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary btn-pill">
                <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Add Account
            </a>
        </div>
        <div class="content-card-body">
            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3"
                  data-live-search="true" data-live-target="users-table-wrap">
                <div class="col-md-5">
                    <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0">
                            <i data-lucide="search" style="width:16px;height:16px;color:var(--text-muted);"></i>
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
                    <button type="submit" class="btn btn-primary btn-pill" style="height: 40px;">
                        <i data-lucide="filter" style="width:14px;height:14px;margin-right:4px;"></i>Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-pill" style="height: 40px;">
                        <i data-lucide="refresh-cw" style="width:14px;height:14px;margin-right:4px;"></i>Reset
                    </a>
                </div>
            </form>

            <div id="users-table-wrap">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table-admin mb-0" data-sortable>
                        <thead>
                            <tr>
                                <th data-sort="name">User</th>
                                <th data-sort="role">Role</th>
                                <th data-sort="unique_id">Unique ID</th>
                                <th data-sort="date">Registration Date</th>
                                <th style="width: 60px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $u)
                                <tr>
                                    <td>
                                        <div class="avatar-cell">
                                            <div class="avatar-inline" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div class="avatar-cell-text">
                                                <div class="avatar-cell-name">{{ $u->name }}</div>
                                                <div class="avatar-cell-sub">{{ $u->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-role {{ $u->role }}">{{ ucfirst($u->role) }}</span>
                                    </td>
                                    <td>
                                        @if ($u->unique_id)
                                            <span style="font-family:monospace;font-size:0.85rem;color:var(--text-primary);font-weight:600;">
                                                {{ $u->unique_id }}
                                            </span>
                                        @else
                                            <span class="text-muted" style="font-size:0.8rem;">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size:0.85rem;">{{ $u->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                                <i data-lucide="more-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border-radius: 12px; border: none; padding: 6px; min-width: 140px;">
                                                <li>
                                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="dropdown-item py-2 rounded-2">
                                                        <i data-lucide="pencil" style="width:14px;height:14px;margin-right:8px;color:#4e73df;"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item py-2 rounded-2 text-danger"
                                                                data-ajax-action="delete"
                                                                data-confirm="Are you sure you want to delete account {{ $u->name }}?">
                                                            <i data-lucide="trash-2" style="width:14px;height:14px;margin-right:8px;"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center py-3">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
            @else
            </div>
                <div class="empty-state">
                    <div class="empty-state-icon-wrap">
                        <i data-lucide="users"></i>
                    </div>
                    <h6>No users found</h6>
                    <p>@if(request('search') || request('role')) No users match your filter criteria. Try a different search. @else No students or tentors registered yet. @endif</p>
                    @if(!request('search') && !request('role'))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm btn-pill mt-2">
                            <i data-lucide="plus" style="width:14px;height:14px;margin-right:4px;"></i>Add Account
                        </a>
                    @else
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm btn-pill mt-2">
                            <i data-lucide="refresh-cw" style="width:14px;height:14px;margin-right:4px;"></i>Reset Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
