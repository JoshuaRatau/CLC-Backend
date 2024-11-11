<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   // database/migrations/xxxx_xx_xx_xxxxxx_create_houses_table.php
public function up()
{
    Schema::create('houses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Links house to user
        $table->string('address');
        $table->text('description')->nullable();
        $table->decimal('latitude', 10, 8); // For geolocation
        $table->decimal('longitude', 11, 8);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('houses');
    }
};
