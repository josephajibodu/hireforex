<?php

namespace App\Http\Controllers;

use App\Models\Trader;
use Illuminate\Http\Request;

class TraderController extends Controller
{
    /**
     * Display a listing of available traders
     */
    public function index()
    {
        $traders = Trader::where('is_available', true)
            ->orderBy('mbg_rate', 'desc')
            ->orderBy('experience_years', 'desc')
            ->paginate(20);

        return view('pages.traders.index', compact('traders'));
    }

    /**
     * Display the specified trader
     */
    public function show(Trader $trader)
    {
        return view('pages.traders.show', compact('trader'));
    }
}
