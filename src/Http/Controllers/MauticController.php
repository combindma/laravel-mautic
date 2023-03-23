<?php

namespace Combindma\Mautic\Http\Controllers;

use Combindma\Mautic\Facades\Mautic;
use Illuminate\Routing\Controller;

class MauticController extends Controller
{
    public function integration()
    {
        Mautic::authorizeApplication();

        return view('mautic::index');
    }
}
