<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SellAdvert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellAdvertController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /** @var SellAdvert $sellAdvert */
        $sellAdvert = $user->sellAdvert;

        return view('pages.sell.history', [
            'orders' => $sellAdvert->orders()->with(['buyer', 'sellAdvert.user'])->latest()->paginate()
        ]);
    }

    public function create()
    {
        return view('pages.sell.create');
    }

    public function show(Order $order)
    {
        return view('pages.sell.details', [
            'order' => $order,
            'dealer' => $order->sellAdvert->user
        ]);
    }
}
