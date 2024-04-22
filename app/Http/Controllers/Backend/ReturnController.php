<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Response;

class ReturnController extends Controller
{
    public function returnRequest()
    {
        $orders = Order::where('return_order', 1)->orderBy('id', 'DESC')->get();

        return Response::json(['orders' => $orders]);
    }

    public function returnRequestApproved($order_id)
    {
        Order::where('id', $order_id)->update(['return_order' => 2]);

        return Response::json(['message' => 'Return Order Approved Successfully']);
    }

    public function completeReturnRequest()
    {
        $orders = Order::where('return_order', 2)->orderBy('id', 'DESC')->get();

        return Response::json(['orders' => $orders]);
    }
}