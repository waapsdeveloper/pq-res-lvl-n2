<?php

namespace App\Traits\Admin;

use App\Models\User;

trait CustomerTrait
{
    public function getCustomerByPhone($phone)
    {

        // check if phone number exist for any user - and has a customer as well 
        // if yes return customer data
        // if not create customer and return data

        $customer = User::where('phone', $phone)->first();  // Search for the customer by phone

        if ($customer) {
            return $customer;
        }

        $customer = User::create([
            'name' => 'walk-in-customer',
            'phone' => $phone,
            'email' => $phone . '@domain.com',  // Use a default or dynamic email
            'role_id' => 0,  // Default role for walk-in customers
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $customer;
    }
}
