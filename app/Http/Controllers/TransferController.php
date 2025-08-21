<?php

namespace App\Http\Controllers;

class TransferController extends Controller
{
    public function index()
    {
        return view('pages.transfer.index');
    }

    public function create()
    {
        return view('pages.transfer.create');
    }
}