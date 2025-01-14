<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Helper
{

    static public function getBase64ImageUrl($image, $folder)
    {
        if ($image) {
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $image);

            $filename = uniqid() . '.png';

            $path = base_path(env(key: 'IMAGE_BASE_FOLDER') . $filename);
            $file = Storage::disk('public')->put('images/' . $folder . '/' . $filename, base64_decode($base64Image));
            // $file = Storage::disk('local')->put('images/' . $folder . '/' . $filename, base64_decode($base64Image));

            if ($file) {
                $imagePath = 'images/' . $folder . '/' . $filename;
                return $imagePath;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }


    public static function deleteImage(string $url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $filePath = ltrim($path, '/'); // Remove any leading slash

        $storage = Storage::disk('public'); // Assuming the disk is 'local'
        // $storage = Storage::disk('local'); // Assuming the disk is 'local'
        if ($storage->exists($filePath)) {
            $storage->delete($filePath); // Delete the file from storage
        } else {
            return "no image found"; // File does not exist
        }

        return true; // File deleted successfully
    }

    static public function returnFullImageUrl($imagePath)
    {
        if (!$imagePath || $imagePath == "") {
            return null;
        }

        // Get the URL from Laravel's filesystem config (the URL from 'public' disk)
        // $baseUrl = url('/'); 
        $baseUrl =  Storage::disk('public')->url('/');
        // $baseUrl =  Storage::disk('local')->url('/');

        // Generate the full image URL by appending the image path
        // Ensure that the image path is properly concatenated to the base URL
        $fullImageUrl = rtrim($baseUrl, '/') . '/' . ltrim($imagePath, '/');

        return $fullImageUrl;
    }
}
