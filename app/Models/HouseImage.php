<?php

// app/Models/HouseImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseImage extends Model
{
    use HasFactory;

    protected $fillable = ['house_id', 'image_path', 'description'];

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
