<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class House extends Model
{
    use HasFactory;

    // Add both 'image_path' and 'image' to the fillable array
    protected $fillable = [
        'user_id',
        'address',
        'description',
        'latitude',
        'longitude',
        'image_path', // Path to the stored image file
        'image'       // Binary data for the image
    ];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
