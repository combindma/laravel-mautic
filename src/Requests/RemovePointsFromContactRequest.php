<?php

namespace Combindma\Mautic\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class RemovePointsFromContactRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected int $contactId, protected int $pointDelta, protected ?array $attributes = null)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/contacts/'.$this->contactId.'/points/minus/'.$this->pointDelta;
    }

    protected function defaultBody(): array
    {
        return $this->attributes ?? [];
    }
}
