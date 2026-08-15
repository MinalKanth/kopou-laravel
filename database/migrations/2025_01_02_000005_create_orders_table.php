<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique(); // human-facing, e.g. KOP-20260815-XXXXX
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            // Order lifecycle vs payment lifecycle are tracked separately —
            // an order can be "processing" while payment is "paid", or
            // cancelled while a refund is still "pending".
            $table->enum('status', ['pending_payment', 'processing', 'shipped', 'delivered', 'cancelled'])
                ->default('pending_payment');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method', 30)->default('razorpay');

            // Razorpay identifiers for reconciliation + signature verification.
            $table->string('razorpay_order_id', 100)->nullable()->index();
            $table->string('razorpay_payment_id', 100)->nullable();
            $table->string('razorpay_signature', 255)->nullable();

            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Shipping address snapshot — never a foreign key to `addresses`,
            // so the order still shows the right address even if the user
            // later edits or deletes the saved address.
            $table->string('shipping_name', 150);
            $table->string('shipping_phone', 20);
            $table->string('shipping_line1', 255);
            $table->string('shipping_line2', 255)->nullable();
            $table->string('shipping_city', 100);
            $table->string('shipping_state', 100);
            $table->string('shipping_pincode', 10);

            $table->text('notes')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
