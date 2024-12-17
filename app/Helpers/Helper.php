<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Helper
{

    static public function getBase64ImageUrl($data)
    {
        // dd($data["image"]);
        $image = isset($data["image"]) ? $data["image"] : null;

        if ($image) {
            // Strip the base64 prefix if it exists
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $image);

            // Create a unique filename for the image
            $filename = uniqid() . '.png'; // You can adjust the file extension as needed

            // Get the path where you want to save the image

            $path = base_path(env('IMAGE_BASE_FOLDER') . $filename);

            // dd($path);

            // Convert base64 to binary data and save it to a file
            // Storage::disk('s3')->put('folder/file.jpg', file_get_contents($filePath));
            // $file = file_put_contents($path, base64_decode($base64Image));
            $file = Storage::disk('public')->put('images/' . $filename, base64_decode($base64Image));

            if ($file) {
                // File saved successfully
                // Now you can use $path to save the image path in your database
                $imagePath = 'images/' . $filename;

                // Save $imagePath in your database for the product
                // Example:
                // $product->image = $imagePath;
                // $product->save();

                // Return the saved image path
                return $imagePath;
            } else {
                // Error saving the file
                return null;
            }
        } else {
            // No image provided
            return null;
        }
    }

    static public function returnFullImageUrl($imagePath)
    {
        if (!$imagePath || $imagePath == "") {
            return null;
        }

        // Get the URL from Laravel's filesystem config (the URL from 'public' disk)
        $baseUrl = url('/'); //Storage::disk('public')->url('');

        // Generate the full image URL by appending the image path
        // Ensure that the image path is properly concatenated to the base URL
        $fullImageUrl = rtrim($baseUrl, '/') . '/' . ltrim($imagePath, '/');

        return $fullImageUrl;
    }

}

?>
