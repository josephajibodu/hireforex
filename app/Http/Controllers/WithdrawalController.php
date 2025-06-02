<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSetting;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(GeneralSetting $generalSetting)
    {
        return view('pages.withdrawal.index', [
            'rate' => getWithdrawalFeePercentage()
        ]);
    }

    public function create(GeneralSetting $generalSetting)
    {
        return view('pages.withdrawal.create', [
            'rate' => getWithdrawalFeePercentage()
        ]);
    }
}
