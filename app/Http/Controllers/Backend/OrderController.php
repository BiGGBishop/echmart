<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; 
use App\Models\Product; 
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function pendingOrders()
    {
        $orders = Order::where('status', 'pending')->orderBy('id', 'DESC')->get();
        return response()->json(['orders' => $orders]);
    }

    public function adminOrderDetails($order_id)
    {
        $order = Order::with('division', 'district', 'state', 'user')->where('id', $order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

        return response()->json(['order' => $order, 'orderItem' => $orderItem]);
    }

    public function adminConfirmedOrder()
    {
        $orders = Order::where('status', 'confirm')->orderBy('id', 'DESC')->get();
        return response()->json(['orders' => $orders]);
    }

    public function adminProcessingOrder()
    {
        $orders = Order::where('status', 'processing')->orderBy('id', 'DESC')->get();
        return response()->json(['orders' => $orders]);
    }

    public function adminDeliveredOrder()
    {
        $orders = Order::where('status', 'deliverd')->orderBy('id', 'DESC')->get();
        return response()->json(['orders' => $orders]);
    }

    public function pendingToConfirm($order_id)
    {
        Order::findOrFail($order_id)->update(['status' => 'confirm']);

        return response()->json(['message' => 'Order Confirm Successfully']);
    }

    public function confirmToProcess($order_id)
    {
        Order::findOrFail($order_id)->update(['status' => 'processing']);

        return response()->json(['message' => 'Order Processing Successfully']);
    }

    public function processToDelivered($order_id)
    {
        $product = OrderItem::where('order_id', $order_id)->get();
        foreach ($product as $item) {
            Product::where('id', $item->product_id)
                ->update(['product_qty' => DB::raw('product_qty-' . $item->qty)]);
        }

        Order::findOrFail($order_id)->update(['status' => 'deliverd']);

        return response()->json(['message' => 'Order Delivered Successfully']);
    }

    public function adminInvoiceDownload($order_id)
    {
        $order = Order::with('division', 'district', 'state', 'user')->where('id', $order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

        // You may not use PDF generation in an API context, consider returning order details as JSON.

        return response()->json(['order' => $order, 'orderItem' => $orderItem]);
    }
}