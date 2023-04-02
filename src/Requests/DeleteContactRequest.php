<?php

namespace Combindma\Mautic\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteContactRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected int $id)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/contacts/'.$this->id.'/delete';
    }
}
