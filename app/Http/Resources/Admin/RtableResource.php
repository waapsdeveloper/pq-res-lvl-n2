<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use App\Models\RTablesBooking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RtableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $obj = self::toObject($this);
        return $obj;
    }

    public static function toObject($obj, $lang = 'en')
    {
        // Check table booking status
        $status = $obj->status;
        $existingBooking = RTablesBooking::where('rtable_id', $obj->id)
            ->where(function ($query) {
                $query->whereBetween('booking_start', [now(), now()->addHour()])
                    ->orWhereBetween('booking_end', [now(), now()->addHour()])
                    ->orWhere(function ($q) {
                        $q->where('booking_start', '<=', now())
                            ->where('booking_end', '>=', now()->addHour());
                    });
            })
            ->first();

        if ($existingBooking) {
            $status = 'reserved';
        }

        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "identifier" => $obj->identifier,
            "no_of_seats" => $obj->no_of_seats,
            "description" => $obj->description,
            "floor" => $obj->floor,
            "status" => ucfirst($status),
            "restaurant_id" => $obj->restaurant_id,
            "total_orders" => $obj->orders_count,
            "qr_code" => "https://pqresfront.spacess.online/tabs/products?table_identifier=$obj->identifier",
            'restaurant_detail' => $obj->restaurantDetail ? [
                "name" => $obj->restaurantDetail->name,
                "address" => $obj->restaurantDetail->address,
                "phone" => $obj->restaurantDetail->phone,
                "email" => $obj->restaurantDetail->email,
                "website" => $obj->restaurantDetail->website,
                "rating" => 4.8,
                "status" => $obj->restaurantDetail->status,
            ] : null,
            'opening_hours' => $obj->restaurantTimings->map(function ($resSchedule) {
                return [
                    'day' => $resSchedule->day,
                    'start_time' => $resSchedule->start_time,
                    'end_time' => $resSchedule->end_time,
                ];
            }),
        ];
    }
}
