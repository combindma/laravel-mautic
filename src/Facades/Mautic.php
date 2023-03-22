<?php

namespace Combindma\Mautic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Combindma\Mautic\Mautic
 */
class Mautic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Combindma\Mautic\Mautic::class;
    }
}
