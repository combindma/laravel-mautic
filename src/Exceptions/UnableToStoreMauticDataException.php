<?php

namespace Combindma\Mautic\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UnableToStoreMauticDataException extends Exception
{
    public function report()
    {
        Log::debug('Unable to store Mautic data in your local disk.');
    }
}
