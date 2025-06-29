<?php

namespace App\Helpers;

use App\Mail\Mail;
use App\Mail\OrderDetailsMail;
use Illuminate\Support\Facades\Storage;
use App\Models\Restaurant;
use Exception;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use Illuminate\Support\Arr;

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

    /**
     * Upload a file and return the path
     */
    static public function uploadFile($file, $folder)
    {
        if ($file && $file->isValid()) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            
            $filePath = Storage::disk('public')->put('images/' . $folder . '/' . $filename, file_get_contents($file));
            
            if ($filePath) {
                return 'images/' . $folder . '/' . $filename;
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
        $baseUrl = url('storage');//Storage::disk('public')->url('/');
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
        $notes = [
            'Please deliver quickly.',
            'Handle with care.',
            'Extra napkins, please.',
            'No contact delivery.',
            'Leave at the front door.',
        ];
        return Arr::random($notes);
    }

    public static function getRandomAddress()
    {
        $addresses = [
            '123 Main St, Anytown, USA',
            '456 Oak Ave, Anytown, USA',
            '789 Pine Ln, Anytown, USA',
            '101 Elm Rd, Anytown, USA',
            '1122 Willow Dr, Anytown, USA',
        ];
        return Arr::random($addresses);
    }
}
