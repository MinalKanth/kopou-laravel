@extends('layouts.admin')
@section('title', 'Users')

@section('admin-content')
<form class="admin-search" method="GET" style="max-width:360px;">
    <input type="text" name="q" value="{{ $q }}" placeholder="Search name or email…">
    <button type="submit" class="admin-btn admin-btn-outline">Search</button>
</form>

<div class="admin-table-wrap">
    <table class="admin-table">
        <thead><tr><th>User</th><th>Orders</th><th>Status</th><th>Role</th><th></th></tr></thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>
                        <span class="admin-table-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        <span class="admin-cell-primary">{{ $user->name }}</span>
                        <div class="admin-cell-sub">{{ $user->email }}</div>
                    </td>
                    <td>{{ $user->orders_count }}</td>
                    <td><span class="status-pill status-{{ $user->is_active ? 'delivered' : 'cancelled' }}">{{ $user->is_active ? 'Active' : 'Suspended' }}</span></td>
                    <td><span class="status-pill status-{{ $user->is_admin ? 'processing' : 'pending' }}">{{ $user->is_admin ? 'Admin' : 'Customer' }}</span></td>
                    <td class="admin-table-actions">
                        @if ($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit">{{ $user->is_active ? 'Suspend' : 'Activate' }}</button>
                            </form>
                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit">{{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}</button>
                            </form>
                        @else
                            <span style="opacity:0.5; font-size:0.8rem;">You</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">
                    <div class="admin-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <p>No users found.</p>
                    </div>
                </td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="admin-pagination">{{ $users->links() }}</div>
@endsection
