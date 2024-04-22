<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Response;

class VendorOrderController extends Controller
{
    public function vendorOrder()
    {
        $id = Auth::user()->id;
        $orderitem = OrderItem::with('order')->where('vendor_id', $id)->orderBy('id', 'DESC')->get();
        return Response::json(['orderItems' => $orderitem]);
    }

    public function vendorReturnOrder()
    {
        $id = Auth::user()->id;
        $orderitem = OrderItem::with('order')->where('vendor_id', $id)->orderBy('id', 'DESC')->get();
        return Response::json(['orderItems' => $orderitem]);
    }

    public function vendorCompleteReturnOrder()
    {
        $id = Auth::user()->id;
        $orderitem = OrderItem::with('order')->where('vendor_id', $id)->orderBy('id', 'DESC')->get();
        return Response::json(['orderItems' => $orderitem]);
    }

    public function vendorOrderDetails($order_id)
    {
        $order = Order::with('division', 'district', 'state', 'user')->where('id', $order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

        return Response::json(['order' => $order, 'orderItems' => $orderItem]);
    }
}