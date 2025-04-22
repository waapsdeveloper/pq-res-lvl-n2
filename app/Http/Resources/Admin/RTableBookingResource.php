<?php

namespace App\Http\Resources\Admin;

use App\Models\RTablesBooking;
use Illuminate\Http\Resources\Json\JsonResource;

class RTableBookingResource extends JsonResource
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
        $status = $obj->status;
        $existingBooking = RTablesBooking::where('rtable_id', $obj->rtable_id)
                ->where(function ($query)  {
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
            } else {
                $status = 'active';                
            }

        return [
            "id" => $obj->id,
            "rtable_id" => $obj->rtable_id,
            "customer" => $obj->customer,
            "role" => $obj->customer && $obj->customer->role ? $obj->customer->role->name : null,
            "order_id" => $obj->order_id,
            "booking_start" => $obj->booking_start,
            "booking_end" => $obj->booking_end,
            "no_of_seats" => $obj->no_of_seats,
            "description" => $obj->description,
            "status" => ucfirst($status),
        ];
    }
}
