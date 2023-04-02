<?php

namespace Combindma\Mautic\Resources;

use Saloon\Http\Connector;

class Resource
{
    public function __construct(protected Connector $connector)
    {
        //
    }
}
