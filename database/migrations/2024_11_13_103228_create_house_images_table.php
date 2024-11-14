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
// database/migrations/create_house_images_table.php
public function up()
{
    Schema::create('house_images', function (Blueprint $table) {
        $table->id();
        $table->foreignId('house_id')->constrained('houses')->onDelete('cascade');
        $table->string('image_path')->nullable(); 
        $table->binary('image_file')->nullable();  
        $table->string('description')->nullable();
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
