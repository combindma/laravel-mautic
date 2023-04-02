<?php

namespace Combindma\Mautic\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected string $email, protected array $attributes = [])
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/contacts/new';
    }

    protected function defaultBody(): array
    {
        return [
            'email' => $this->email,
            'ipAddress' => request()->ip(),
            ...$this->attributes,
        ];
    }
}
