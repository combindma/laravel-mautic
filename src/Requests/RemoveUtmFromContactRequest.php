<?php

namespace Combindma\Mautic\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class RemoveUtmFromContactRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected int $utmId, protected int $contactId)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/contacts/'.$this->contactId.'/utm/'.$this->utmId.'/remove';
    }
}
