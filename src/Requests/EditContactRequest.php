<?php

namespace Combindma\Mautic\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class EditContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $contactId, protected array $attributes)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/contacts/'.$this->contactId.'/edit';
    }

    protected function defaultBody(): array
    {
        return [
            'ipAddress' => request()->ip(),
            'lastActive' => now('UTC')->format('Y-m-d H:m:i'),
            ...$this->attributes,
        ];
    }
}
