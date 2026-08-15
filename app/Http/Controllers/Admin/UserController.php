<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::withCount('orders')
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($q2) => $q2
                ->where('name', 'like', '%'.$request->q.'%')
                ->orWhere('email', 'like', '%'.$request->q.'%')))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', ['users' => $users, 'q' => $request->q]);
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 422, "You can't deactivate your own account.");
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('status', $user->is_active ? 'User activated.' : 'User suspended.');
    }

    public function toggleAdmin(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 422, "You can't change your own admin access.");
        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('status', $user->is_admin ? 'Granted admin access.' : 'Revoked admin access.');
    }
}
