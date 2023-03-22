<?php

namespace Combindma\Mautic\Http\Controllers;

use Combindma\Mautic\Exceptions\UnableToStoreMauticDataException;
use Combindma\Mautic\Facades\Mautic;
use Illuminate\Support\Facades\Storage;

class MauticController extends Controller
{
    public function login()
    {
        Mautic::getAccessTokenData();

        return view('mautic::index');
    }

    public function callback()
    {
        $mauticData = Mautic::getAccessTokenData();
        // The file could not be written to disk...
        if (! Storage::disk('local')->put(config('mautic.fileName'), json_encode($mauticData))) {
            throw new UnableToStoreMauticDataException('Unable to store Mautic data.');
        }
    }
}
