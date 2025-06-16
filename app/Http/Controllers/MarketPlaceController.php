<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;

class MarketPlaceController extends Controller
{
    public function index()
    {
        return view('pages.marketplace.index');
    }

    public function active_orders()
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->where('status', OrderStatus::Paid)
            ->with(['giftCard'])
            ->latest()
            ->paginate(10);

        return view('pages.marketplace.active', compact('orders'));
    }

    public function all_orders()
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->with(['giftCard'])
            ->latest()
            ->paginate(10);

        return view('pages.marketplace.history', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['giftCard']);

        return view('pages.marketplace.details', compact('order'));
    }
}