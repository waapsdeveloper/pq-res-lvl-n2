<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCoupon;
use App\Http\Requests\Admin\Coupon\UpdateCoupon;
use App\Http\Resources\Admin\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
   
    public function availableValidCoupon(Request $request){
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:coupons,code',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $coupon = Coupon::where('code', $request->code)->first();
        if(!$coupon->isValid()){
            return ServiceResponse::error('Coupon is not available', ['coupon' => null]);
        }
        

        return ServiceResponse::success("Coupon is available", ['coupon' => $coupon]);

    }

    public function updateCouponUsage(Request $request){ 
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:coupons,code',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $coupon = Coupon::where('code', $request->code)->first();
        if(!$coupon->isValid()){
            return ServiceResponse::error('Coupon is not available', ['coupon' => $coupon]);
        }
        $coupon->used_count += 1;
        $coupon->save();

        return ServiceResponse::success("Coupon usage updated successfully", ['coupon' => $coupon]);
    }
}
