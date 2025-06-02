<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArbitrageTradeController extends Controller
{
    public function index()
    {
        return view('pages.trade.index');
    }

    public function active_trades()
    {
        return view('pages.trade.active-trades');
    }

    public function all_trades()
    {
        return view('pages.trade.all-trades');
    }
}
