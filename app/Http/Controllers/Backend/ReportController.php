<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function searchByDate(Request $request)
    {
        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');

        $orders = Order::where('order_date', $formatDate)->latest()->get();

        return Response::json(['orders' => $orders, 'formatDate' => $formatDate]);
    }

    public function searchByMonth(Request $request)
    {
        $month = $request->month;
        $year = $request->year_name;

        $orders = Order::where('order_month', $month)->where('order_year', $year)->latest()->get();

        return Response::json(['orders' => $orders, 'month' => $month, 'year' => $year]);
    }

    public function searchByYear(Request $request)
    {
        $year = $request->year;

        $orders = Order::where('order_year', $year)->latest()->get();

        return Response::json(['orders' => $orders, 'year' => $year]);
    }

    public function orderByUser()
    {
        $users = User::where('role', 'user')->latest()->get();

        return Response::json(['users' => $users]);
    }

    public function searchByUser(Request $request)
    {
        $users = $request->user;
        $orders = Order::where('user_id', $users)->latest()->get();

        return Response::json(['orders' => $orders, 'user' => $users]);
    }
}