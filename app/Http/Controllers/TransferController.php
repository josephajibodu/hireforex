<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSetting;

class TransferController extends Controller
{
    public function index(GeneralSetting $generalSetting)
    {
        return view('pages.transfer.index', [
            'rate' => getWithdrawalFeePercentage()
        ]);
    }

    public function create(GeneralSetting $generalSetting)
    {
        return view('pages.transfer.create', [
            'rate' => getWithdrawalFeePercentage()
        ]);
    }
}
