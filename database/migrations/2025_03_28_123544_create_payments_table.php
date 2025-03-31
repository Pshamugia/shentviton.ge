<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_hash')->nullable();
            $table->decimal('amount', 10, 2);
            $table->json('cart_ids');
            $table->string('currency', 10);
            $table->string('status', 50);
            $table->string('gateway', 50);
            $table->string('gateway_transaction_id')->nullable();


            $table->string('u_name')->nullable();
            $table->string('u_email')->nullable();
            $table->string('u_phone')->nullable();
            $table->text('u_address')->nullable();
            $table->boolean('is_parent_order')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
