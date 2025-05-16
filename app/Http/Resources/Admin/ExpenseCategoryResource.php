<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'restaurant' => [
                'id' => $this->restaurant->id,
                'name' => $this->restaurant->name,
            ],
            'category_name' => $this->category_name,
            'daily_estimate' => $this->daily_estimate,
            'weekly_estimate' => $this->weekly_estimate,
            'monthly_estimate' => $this->monthly_estimate,
            'description' => $this->description,
           'image' => \App\Helpers\Helper::returnFullImageUrl($this->image),
        'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}