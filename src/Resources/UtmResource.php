<?php

namespace Combindma\Mautic\Resources;

use Combindma\Mautic\Requests\AddUtmToContactRequest;
use Combindma\Mautic\Requests\RemoveUtmFromContactRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Saloon\Http\Response;

class UtmResource extends Resource
{
    public function addUtmTag(int $contactId, array $attributes): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new AddUtmToContactRequest($contactId, $attributes));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }

    public function removeUtmTag(int $utmId, int $contactId): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new RemoveUtmFromContactRequest($utmId, $contactId));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }
}
