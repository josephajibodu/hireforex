<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealerDashboardController extends Controller
{
    public function __invoke()
    {
        return view('pages.dealer.home', [
            'user' => Auth::user()
        ]);
    }
}
