<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReviewController extends Controller
{
    public function storeReview(Request $request)
    {
        $product = $request->product_id;
        $vendor = $request->hvendor_id;

        $request->validate([
            'comment' => 'required',
        ]);

        Review::insert([
            'product_id' => $product,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'rating' => $request->quality,
            'vendor_id' => $vendor,
            'created_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Review Will Approve By Admin',
            'alert-type' => 'success',
        ];

        return Response::json(['notification' => $notification]);
    }

    public function pendingReview()
    {
        $review = Review::where('status', 0)->orderBy('id', 'DESC')->get();
        return Response::json(['review' => $review]);
    }

    public function reviewApprove($id)
    {
        Review::where('id', $id)->update(['status' => 1]);

        $notification = [
            'message' => 'Review Approved Successfully',
            'alert-type' => 'success',
        ];

        return Response::json(['notification' => $notification]);
    }

    public function publishReview()
    {
        $review = Review::where('status', 1)->orderBy('id', 'DESC')->get();
        return Response::json(['review' => $review]);
    }

    public function reviewDelete($id)
    {
        Review::findOrFail($id)->delete();

        $notification = [
            'message' => 'Review Deleted Successfully',
            'alert-type' => 'success',
        ];

        return Response::json(['notification' => $notification]);
    }

    public function vendorAllReview()
    {
        $id = Auth::user()->id;

        $review = Review::where('vendor_id', $id)->where('status', 1)->orderBy('id', 'DESC')->get();
        return Response::json(['review' => $review]);
    }
}