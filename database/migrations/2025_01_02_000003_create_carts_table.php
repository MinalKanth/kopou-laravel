<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A cart belongs to either a logged-in user OR a guest session
        // (never both) so we can merge the guest cart into the account
        // cart on login without a foreign-key conflict.
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('session_id', 100)->nullable();
            $table->timestamps();

            $table->index('session_id');
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
