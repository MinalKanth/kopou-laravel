<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // bcrypt/argon2id hash, never plaintext
            $table->string('phone', 20)->nullable();
            $table->boolean('is_active')->default(true); // for admin-side account suspension later
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Section 11: account deletion should be recoverable, not destructive

            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
