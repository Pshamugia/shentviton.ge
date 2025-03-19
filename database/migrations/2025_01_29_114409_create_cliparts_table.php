<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cliparts', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // Stores image path
            $table->string('category'); // Stores category (All, Sport, Funny)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliparts');
    }
};
