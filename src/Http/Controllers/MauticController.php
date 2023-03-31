<?php

namespace Combindma\Mautic\Http\Controllers;

use Combindma\Mautic\Facades\Mautic;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MauticController extends Controller
{
    public function login()
    {
        return Mautic::authorize();
    }

    public function callback(Request $request)
    {
        Mautic::requestToken($request);

        return view('mautic::index');
    }
}
