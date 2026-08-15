<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('account.dashboard');
        }

        // ->fulfill() checks the signed-URL hash matches the user's email
        // and that the link hasn't expired before marking verified.
        $request->fulfill();

        return redirect()->route('account.dashboard')->with('status', 'Your email is verified.');
    }
}
