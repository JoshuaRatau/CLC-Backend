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
// database/migrations/xxxx_xx_xx_xxxxxx_create_house_images_table.php
public function up()
{
    Schema::create('house_images', function (Blueprint $table) {
        $table->id();
        $table->foreignId('house_id')->constrained('houses')->onDelete('cascade'); // Links image to house
        $table->string('image_path'); // Path to store image file
        $table->text('description')->nullable(); // Description of the damage or image
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
        Schema::dropIfExists('house_images');
    }
};
