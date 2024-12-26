<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Helper
{

    static public function getBase64ImageUrl($image)
    {
        // $image = isset($data["image"]) ? $data["image"] : null;

        if ($image) {
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $image);

            $filename = uniqid() . '.png';

            $path = base_path(env('IMAGE_BASE_FOLDER') . $filename);
            $file = Storage::disk('local')->put('images/' . $filename, base64_decode($base64Image));

            if ($file) {
                $imagePath = 'images/' . $filename;
                // dd($path, $file, $imagePath);
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
        $path = parse_url($url, PHP_URL_PATH); // Extract the file path from the URL
        $filePath = public_path($path); // Convert to the full server path

        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
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
