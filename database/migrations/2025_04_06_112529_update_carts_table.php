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
        Schema::table('carts', function (Blueprint $table) {

            if (!Schema::hasColumn('carts', 'visitor_hash')) {
                $table->string('visitor_hash', 191)->nullable(false);
            }

            if (!Schema::hasColumn('carts', 'payment_id')) {
                $table->integer('payment_id')->nullable(true)->default(NULL);
            }

            if (!Schema::hasColumn('carts', 'default_img')) {
                $table->tinyInteger('default_img')->nullable(false)->default(1);
            }

            if (!Schema::hasColumn('carts', 'design_front_image')) {
                $table->string('design_front_image', 255)->nullable();
            }

            if (!Schema::hasColumn('carts', 'design_back_image')) {
                $table->string('design_back_image', 255)->nullable();
            }

            if (!Schema::hasColumn('carts', 'front_assets')) {
                $table->string('front_assets', 255)->nullable();
            }

            if (!Schema::hasColumn('carts', 'back_assets')) {
                $table->string('back_assets', 255)->nullable();
            }

            if (!Schema::hasColumn('carts', 'status')) {
                $table->enum('status', ['pending', 'paid', 'processed'])->default('pending');
            }

            if (!Schema::hasColumn('carts', 'size')) {
                $table->string('size')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
