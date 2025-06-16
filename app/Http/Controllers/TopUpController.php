<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopUpController extends Controller
{
    public function index()
    {
        return view('pages.top-up.index');
    }

    public function create()
    {
        $type = request()->query('type');

        return view('pages.top-up.create', compact('type'));
    }

    public function history()
    {
        return view('pages.top-up.history');
    }
}
