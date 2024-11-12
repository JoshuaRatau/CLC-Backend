<?php

namespace App\Http\Controllers;

use App\Models\House;
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validate image file
            ]);
            Log::info('Validation passed');
        } catch (\Exception $e) {
            Log::error('Validation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Validation failed'], 422);
        }
    
        $imagePath = null;
    
        // Check if an image file was uploaded
        if ($request->hasFile('image')) {
            Log::info('Image file detected');
            $file = $request->file('image');
    
            if ($file->isValid()) {
                $imagePath = $file->store('houses', 'public');
                Log::info('Image stored successfully at path: ' . $imagePath);
            } else {
                Log::error('Uploaded image file is invalid.');
            }
        } else {
            Log::info('No image file uploaded in the request.');
        }
    
        try {
            // Log each parameter before creating the record
            Log::info('User ID: ' . Auth::id());
            Log::info('Address: ' . $request->address);
            Log::info('Description: ' . $request->description);
            Log::info('Latitude: ' . $request->latitude);
            Log::info('Longitude: ' . $request->longitude);
            Log::info('Image Path: ' . $imagePath);
    
            // Create a new house record in the database
            $house = House::create([
                'user_id' => Auth::id(),
                'address' => $request->address,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'image_path' => $imagePath,
            ]);
    
            Log::info('House created successfully with ID: ' . $house->id);
    
            return response()->json(['house' => $house, 'message' => 'House added successfully'], 201);
    
        }catch (\Exception $e) {
    Log::error('Failed to create house record: ' . $e->getMessage());
    Log::error('Exception Trace: ' . $e->getTraceAsString());  // Add trace log for debugging
    return response()->json(['error' => 'Failed to create house record. Please try again.'], 500);
}

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

