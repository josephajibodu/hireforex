<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SellAdvert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyUSDController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.buy.history', [
            'orders' => $user->buyOrders()->with(['buyer', 'sellAdvert.user'])->latest()->paginate()
        ]);
    }

    public function create()
    {
        return view('pages.buy.available-sellers');
    }

    public function show(Order $order)
    {
        return view('pages.buy.details', [
            'order' => $order,
            'dealer' => $order->sellAdvert->user
        ]);
    }
}
