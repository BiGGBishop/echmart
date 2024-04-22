<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function allCoupons()
    {
        $coupons = Coupon::latest()->get();
        return response()->json(['coupons' => $coupons]);
    }

    public function storeCoupon(Request $request)
    {
        Coupon::insert([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Coupon Inserted Successfully']);
    }

    public function updateCoupon(Request $request)
    {
        $coupon_id = $request->id;

        Coupon::findOrFail($coupon_id)->update([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Coupon Updated Successfully']);
    }

    public function deleteCoupon($id)
    {
        Coupon::findOrFail($id)->delete();

        return response()->json(['message' => 'Coupon Deleted Successfully']);
    }
}