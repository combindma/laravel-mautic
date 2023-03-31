<?php

namespace Combindma\Mautic\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UnableToGetMauticTokensException extends Exception
{
    public function report()
    {
        Log::debug('Unable to get Mautic tokens from your local disk.');
    }
}
