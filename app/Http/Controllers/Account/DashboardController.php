<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Account overview. Order/wishlist/address counts are stubbed at
     * zero until Phase 6 (cart), Phase 7 (checkout) and the wishlist's
     * eventual DB-backed table exist — the dashboard layout is built
     * now so those phases only need to fill in real numbers.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('account.dashboard', [
            'user' => $user,
            'stats' => [
                'orders' => 0,
                'wishlist' => count($request->session()->get('wishlist_slugs', [])),
                'addresses' => 0,
            ],
        ]);
    }
}
