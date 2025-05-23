<?php
namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->category_name,
                'restaurant' => $this->category->restaurant, // Return full restaurant object
            ],
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'date' => $this->date,
            'image' => \App\Helpers\Helper::returnFullImageUrl($this->image),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}