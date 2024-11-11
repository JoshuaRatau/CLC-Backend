<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;         // Import the User model
use App\Models\HouseImage;    // Import the HouseImage model

class House extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address', 'description', 'latitude', 'longitude'];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the HouseImage model
    public function images()
    {
        return $this->hasMany(HouseImage::class);
    }
}
