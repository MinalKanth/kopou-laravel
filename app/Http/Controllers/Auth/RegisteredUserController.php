<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']), // redundant with the 'hashed' cast, kept explicit for clarity
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate(); // Section 11: prevent session fixation on privilege change
        $this->cartService->mergeGuestCartIntoUser($request, $user);

        return redirect()->route('account.dashboard')
            ->with('status', 'Welcome to KOPOU! Please check your email to verify your address.');
    }
}
