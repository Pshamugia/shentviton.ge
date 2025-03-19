<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('product_colors', function (Blueprint $table) {
         $table->string('back_image')->after('front_image');
    });
}

public function down()
{
    Schema::table('product_colors', function (Blueprint $table) {
         $table->dropColumn('back_image');
    });
}

};
