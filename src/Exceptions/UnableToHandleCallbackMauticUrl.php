<?php

namespace Combindma\Mautic\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UnableToHandleCallbackMauticUrl extends Exception
{
    public function report()
    {
        Log::debug('The callback url does not have state or code inputs.');
    }
}
