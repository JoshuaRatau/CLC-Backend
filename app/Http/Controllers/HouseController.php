<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/HouseController.php
namespace App\Http\Controllers;

use App\Models\House;
use App\Models\HouseImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HouseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $house = House::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['house' => $house, 'message' => 'House added successfully'], 201);
    }

    public function addImage(Request $request, House $house)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        // Save image file
        $path = $request->file('image')->store('house_images', 'public');

        $image = $house->images()->create([
            'image_path' => $path,
            'description' => $request->description,
        ]);

        return response()->json(['image' => $image, 'message' => 'Image added successfully'], 201);
    }

    public function destroy(House $house)
    {
        $house->delete(); // This will also delete all associated images due to cascade
        return response()->json(['message' => 'House and all associated images deleted successfully']);
    }

    public function deleteImage(House $house, HouseImage $image)
    {
        // Check if the image belongs to the house
        if ($image->house_id !== $house->id) {
            return response()->json(['error' => 'Image does not belong to this house'], 403);
        }

        // Delete the image file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete the record from the database
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}

