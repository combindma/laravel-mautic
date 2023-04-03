<?php

namespace Combindma\Mautic\Resources;

use Combindma\Mautic\Requests\AddContactToSegmentRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Saloon\Http\Response;

class SegmentResource extends Resource
{
    public function addContact(int $segmentId, int $contactId): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new AddContactToSegmentRequest($segmentId, $contactId));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }
}
