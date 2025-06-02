<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSetting;

class BuyUSDTController extends Controller
{
    public function index(GeneralSetting $generalSetting)
    {
        return view('pages.buy-usdt.index', [
            'min_amount' => $generalSetting->minimum_usdt_withdrawal
        ]);
    }

    public function create(GeneralSetting $generalSetting)
    {
        return view('pages.buy-usdt.create', [
            'min_amount' => $generalSetting->minimum_usdt_withdrawal
        ]);
    }
}
