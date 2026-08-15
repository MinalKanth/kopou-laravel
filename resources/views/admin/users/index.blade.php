@extends('layouts.admin')
@section('title', 'Users')

@section('admin-content')
<form class="admin-search" method="GET" style="max-width:360px;">
    <input type="text" name="q" value="{{ $q }}" placeholder="Search name or email…">
    <button type="submit" class="btn btn-outline">Search</button>
</form>

<div class="admin-table-wrap" style="margin-top:1rem;">
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Orders</th><th>Status</th><th>Admin</th><th></th></tr></thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->orders_count }}</td>
                    <td><span class="status-pill status-{{ $user->is_active ? 'delivered' : 'cancelled' }}">{{ $user->is_active ? 'Active' : 'Suspended' }}</span></td>
                    <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
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
                <tr><td colspan="6" class="empty-state">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="admin-pagination">{{ $users->links() }}</div>
@endsection
