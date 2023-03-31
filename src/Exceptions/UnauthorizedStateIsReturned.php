<?php

namespace Combindma\Mautic\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UnauthorizedStateIsReturned extends Exception
{
    public function report()
    {
        Log::debug('The state returned by Mautic is not the same stored in the session.');
    }
}
