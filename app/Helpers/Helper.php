<?php

namespace App\Helpers;

use App\Mail\Mail;
use App\Mail\OrderDetailsMail;
use Illuminate\Support\Facades\Storage;
use App\Models\Restaurant;
use Exception;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

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
        $baseUrl =  url('/'); //Storage::disk('public')->url('/');
        // $baseUrl =  Storage::disk('local')->url('/');

        // Generate the full image URL by appending the image path
        // Ensure that the image path is properly concatenated to the base URL
        $fullImageUrl = rtrim($baseUrl, '/') . '/' . ltrim($imagePath, '/');

        return $fullImageUrl;
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

    

    public static function sendEmail($to, $subject, $view, $data = [])
    {
        try {
            // if ($view === 'emails.order_details') {
            //     if (isset($data['order'])) {
            //         Mail::to($to)->send(new OrderDetailsMail($data['order']));
            //     } else {
            //         Log::error("Order data is missing for order details email.");
            //         return false;
            //     }
            // } else {
            //     Mail::send($view, $data, function ($message) use ($to, $subject) {
            //         $message->to($to)
            //                 ->subject($subject);
            //     });
            // }
            return true; // Email sent successfully
        } catch (Exception $e) {
            Log::error("Error sending email: " . $e->getMessage(), [
                'to' => $to,
                'subject' => $subject,
                'view' => $view,
                'data' => $data
            ]);
            return false; // Email sending failed
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
