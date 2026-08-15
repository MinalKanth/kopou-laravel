<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Creates (or promotes) a default admin login for local/staging use.
     * CHANGE THIS PASSWORD before deploying anywhere real.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kopou.test'],
            [
                'name' => 'KOPOU Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
