<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use App\Models\Restaurant;
use Exception;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class Helper
{

    public static function getBase64ImageUrl($image, $folder)
    {
        if ($image) {
            // Remove the base64 prefix from the image string
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $image);

            // Generate a unique filename for the image
            $filename = uniqid() . '.png';

            // Decode the base64 image
            $decodedImage = base64_decode($base64Image);

            if ($decodedImage === false) {
                throw new Exception("Invalid base64 image data.");
            }

            // Upload the image to the S3 bucket
            $filePath = 'images/' . $folder . '/' . $filename;
            $uploaded = Storage::disk(env('STORAGE_DISK'))->put($filePath, $decodedImage, 'public');

            if ($uploaded) {
                // Return the public URL of the uploaded image
                return Storage::disk(env('STORAGE_DISK'))->url($filePath);
            } else {
                // throw new Exception("Failed to upload image to S3.");
                return null; // Handle the error as needed
            }
        } else {
            return null;
        }
    }

    public static function deleteImage(string $url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $filePath = ltrim($path, '/'); // Remove any leading slash

        $storage = Storage::disk(env('STORAGE_DISK')); // Assuming the disk is 'local'
        // $storage = Storage::disk('local'); // Assuming the disk is 'local'
        if ($storage->exists($filePath)) {
            $storage->delete($filePath); // Delete the file from storage
        } else {
            return "no image found"; // File does not exist
        }

        return true; // File deleted successfully
    }

    public static function returnFullImageUrl($path)
    {
        // Ensure the path is not empty
        if (empty($path) || $path === '/') {
            return null; // or return a default image URL
        }

        // Get the S3 disk instance
        $disk = Storage::disk('s3');

        // Check if the file exists in the S3 bucket
        if (!$disk->exists($path)) {
            return null; // or return a default image URL
        }

        // Generate a pre-signed URL valid for 10 minutes
        $client = Storage::disk('s3')->getClient(); // Get the AWS S3 client
        $bucket = env('AWS_BUCKET');
        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $path,
        ]);

        $request = $client->createPresignedRequest($command, '+10 minutes');

        try {
            // Return the pre-signed URL
            return (string) $request->getUri();
        } catch (Exception $e) {
            \Log::error("Error generating pre-signed URL: " . $e->getMessage());
            return null; // Return null or handle the error as needed
        }
    }

    static public function getActiveRestaurantId()
    {
        $activeRestaurant = Restaurant::where('is_active', 1)
            ->with('timings', 'settings', 'rTables')
            // , 'categories', 'products', 'users'
            ->first();

        if (!$activeRestaurant) {
            $activeRestaurant = Restaurant::where('id', 1)
                ->with('timings', 'settings', 'rTables')
                ->first();
        }
        if ($activeRestaurant) {
            $activeRestaurant['image'] = Helper::returnFullImageUrl($activeRestaurant->image);
            $activeRestaurant['favicon'] = Helper::returnFullImageUrl($activeRestaurant->favicon);
            $activeRestaurant['logo'] = Helper::returnFullImageUrl($activeRestaurant->logo);
        }

        return $activeRestaurant;
    }

    public static function sendPusherToUser($data, $trigger, $event)
    {

        return true; // for now it is not working on server
        try {
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
                'cluster' => env('PUSHER_APP_CLUSTER')
            ]);

            $pusher->trigger($trigger, $event, $data);
        } catch (Exception $e) {
            Log::debug("Pusher Error", ['error' => $e->getMessage()]);
        }
    }
    public static function getRandomOrderNote()
    {
        $phrases = [
            "Customer prefers extra spicy food.",
            "Add extra napkins and cutlery.",
            "Customer has requested a window seat.",
            "Please prepare the dish gluten-free.",
            "Add a birthday candle to the dessert.",
            "Customer allergic to nuts. Avoid any nut-based items.",
            "Customer prefers less salt in food.",
            "Include a thank you card with the order.",
            "Serve with extra dipping sauces.",
            "Customer will pick up the order at 6 PM.",
            "Please ensure contactless delivery.",
            "Customer requested a call before delivery.",
            "Include extra ketchup and mustard packets.",
            "Customer is celebrating an anniversary.",
            "Add a complimentary drink if possible.",
            "Customer prefers low-fat dressing.",
            "Separate sauces from the main dish.",
            "Add a note saying 'Happy Birthday!'",
            "Customer will bring their own wine.",
            "Provide a high chair for a toddler."
        ];

        return $phrases[array_rand($phrases)];
    }
}
