<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class AllUserController extends Controller
{
    public function userAccount()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);

        return Response::json(['userData' => $userData]);
    }

    public function userOrderPage()
    {
        $id = Auth::user()->id;
        $orders = Order::where('user_id', $id)->orderBy('id', 'DESC')->get();

        return Response::json(['orders' => $orders]);
    }

    public function userOrderDetails($order_id)
    {
        $order = Order::with('division', 'district', 'state', 'user')
            ->where('id', $order_id)
            ->where('user_id', Auth::id())
            ->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

        return Response::json(['order' => $order, 'orderItem' => $orderItem]);
    }

    public function userOrderInvoice($order_id)
    {
        $order = Order::with('division', 'district', 'state', 'user')
            ->where('id', $order_id)
            ->where('user_id', Auth::id())
            ->first();
        $orderItem = OrderItem::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();

        $pdf = Pdf::loadView('frontend.order.order_invoice', compact('order', 'orderItem'))->setPaper('a4')->setOption([
            'tempDir' => public_path(),
            'chroot' => public_path(),
        ]);

        return $pdf->stream();
    }

    public function returnOrder(Request $request, $order_id)
    {
        Order::findOrFail($order_id)->update([
            'return_date' => Carbon::now()->format('d F Y'),
            'return_reason' => $request->return_reason,
            'return_order' => 1,
        ]);

        $notification = [
            'message' => 'Return Request Send Successfully',
            'alert-type' => 'success',
        ];

        return Response::json(['notification' => $notification]);
    }

    public function returnOrderPage()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('return_reason', '!=', NULL)
            ->orderBy('id', 'DESC')
            ->get();

        return Response::json(['orders' => $orders]);
    }

    public function orderTracking(Request $request)
    {
        $invoice = $request->code;
        $track = Order::where('invoice_no', $invoice)->first();

        if ($track) {
            return Response::json(['track' => $track]);
        } else {
            $notification = [
                'message' => 'Invoice Code Is Invalid',
                'alert-type' => 'error',
            ];

            return Response::json(['notification' => $notification], 400);
        }
    }
}