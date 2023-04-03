<?php

namespace Combindma\Mautic\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class AddContactToSegmentRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(protected int $segmentId, protected int $contactId)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return '/segments/'.$this->segmentId.'/contact/'.$this->contactId.'/add';
    }
}
