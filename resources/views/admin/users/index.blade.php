@extends('layouts.dashboard')

@section('title', 'Manage Users - Eduria')
@section('page-title', 'Manage Users')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2"
             style="border-radius: 14px; border: none; font-size: 0.9rem; font-weight: 500;" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content-card shadow-sm">
        <div class="card-header">
            <span><i class="fas fa-users me-2"></i>Student & Tentor Accounts</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary" style="border-radius: 10px; font-weight: 600;">
                <i class="fas fa-plus me-1"></i>Add Account
            </a>
        </div>
        <div class="card-body p-0">
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
                    {{ $users->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h6>No users yet</h6>
                    <p>No students or tentors registered yet. Click "Add Account" to add one.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mt-2" style="border-radius: 10px;">
                        <i class="fas fa-plus me-1"></i>Add Account
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
