<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ActiveUserController extends Controller
{
    public function AllUser(){
        $users = User::where('role','user')->latest()->get();
        return response()->json(['users' => $users]);
    }

    public function AllVendor(){
        $vendors = User::where('role','vendor')->latest()->get();
        return response()->json(['vendors' => $vendors]);
    }
} 