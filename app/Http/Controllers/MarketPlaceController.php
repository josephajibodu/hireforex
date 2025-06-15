<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketPlaceController extends Controller
{
    public function index()
    {
        return view('pages.marketplace.index');
    }

    public function active_orders()
    {
        return view('pages.marketplace.active');
    }

    public function all_orders()
    {
        return view('pages.marketplace.history');
    }
}
