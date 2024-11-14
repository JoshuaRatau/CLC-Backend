<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use App\Models\House;
use App\Models\HouseImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HouseController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Store method called in HouseController');
    
        // Validate the request inputs
        try {
            $request->validate([
                'address' => 'required|string|max:255',
                'description' => 'nullable|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
            Log::info('Validation passed');
        } catch (\Exception $e) {
            Log::error('Validation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Validation failed'], 422);
        }
    
        try {
            // Log each parameter before creating the record
            Log::info('User ID: ' . Auth::id());
            Log::info('Address: ' . $request->address);
            Log::info('Description: ' . $request->description);
            Log::info('Latitude: ' . $request->latitude);
            Log::info('Longitude: ' . $request->longitude);
    
            // Create a new house record in the database
            $house = House::create([
                'user_id' => Auth::id(),
                'address' => $request->address,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
    
            Log::info('House created successfully with ID: ' . $house->id);
    
            return response()->json(['house' => $house, 'message' => 'House added successfully'], 201);
    
        } catch (\Exception $e) {
            Log::error('Failed to create house record: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create house record. Please try again.'], 500);
        }
    }
    

    public function getUserHouses()
    {
        $userHouses = House::where('user_id', Auth::id())->get();

        if ($userHouses->isEmpty()) {
            return response()->json(['message' => 'No houses found for this user'], 404);
        }

        return response()->json(['houses' => $userHouses], 200);
    }


    public function addImage(Request $request, $houseId)
    {
        // Log the authenticated user's ID and the requested house ID
        $userId = Auth::id();
        \Log::info("Authenticated User ID: $userId");
        \Log::info("Requested House ID: $houseId");
    
        // Ensure that the authenticated user owns the specified house
        $house = House::where('id', $houseId)->where('user_id', $userId)->first();
    
        if (!$house) {
            \Log::error('Unauthorized access or house not found for this user.');
            return response()->json(['error' => 'Unauthorized or House not found.'], 403);
        }
    
        // Check if the image file exists in the request
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'Image file missing.'], 400);
        }
    
        // Validate the image and description fields
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);
    
        // Store the uploaded image file in the 'house_images' directory
        $filePath = $request->file('image')->store('house_images', 'public');
    
        // Create a new house image record
        $houseImage = HouseImage::create([
            'house_id' => $houseId,
            'image_path' => $filePath,
            'image_file' => $imageContent,  // Store the raw file data here
            'description' => $request->description,
        ]);
    
        return response()->json(['house_image' => $houseImage, 'message' => 'Image uploaded successfully.'], 201);
    }
    

    public function getImages($houseId)
    {
        // Log the authenticated user's ID and the requested house ID
        $userId = Auth::id();
        \Log::info("Authenticated User ID: $userId");
        \Log::info("Requested House ID: $houseId");
    
        // Ensure that the authenticated user owns the specified house
        $house = House::where('id', $houseId)->where('user_id', $userId)->first();
    
        if (!$house) {
            \Log::error('Unauthorized access or house not found for this user.');
            return response()->json(['error' => 'Unauthorized or House not found.'], 403);
        }
    
        // Retrieve all images associated with the specified house
        $images = HouseImage::where('house_id', $houseId)->get(['image_path', 'description']);
    
        // Check if images are found
        if ($images->isEmpty()) {
            return response()->json(['message' => 'No images found for this house.'], 404);
        }
    
        // Map the image URLs for easy access on the frontend
        $images = $images->map(function($image) {
            $image->image_url = asset('storage/' . $image->image_path);
            return $image;
        });
    
        return response()->json(['images' => $images], 200);
    }


    

    public function destroy(House $house)
    {
        try {
            $house->delete();  // This triggers the cascade delete on related images

            return response()->json(['message' => 'House and all associated images deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete the house'], 500);
        }
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

