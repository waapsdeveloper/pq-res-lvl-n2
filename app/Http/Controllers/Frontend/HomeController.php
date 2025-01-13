<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;


class HomeController extends Controller
{
    public function roles()
    {
        $roles = Role::get();
        return ServiceResponse::success('roles are retrived successfully', ['data' => $roles]);
    }
}
