<?php

namespace Combindma\Mautic\Http\Controllers;

use Combindma\Mautic\MauticAuthConnector;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MauticController extends Controller
{
    public function login()
    {
        $authConnector = new MauticAuthConnector;

        return $authConnector->authorize();
    }

    public function callback(Request $request)
    {
        $authConnector = new MauticAuthConnector;
        $authConnector->requestToken($request);

        return view('mautic::index');
    }
}
