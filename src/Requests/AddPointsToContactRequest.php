<?php

namespace Combindma\Mautic\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class AddPointsToContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected int $contactId, protected int $pointDelta, protected ?array $attributes = null)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/contacts/'.$this->contactId.'/points/plus/'.$this->pointDelta;
    }

    protected function defaultBody(): array
    {
        return $this->attributes ?? [];
    }
}
