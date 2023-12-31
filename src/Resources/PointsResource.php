<?php

namespace Combindma\Mautic\Resources;

use Combindma\Mautic\Requests\AddPointsToContactRequest;
use Combindma\Mautic\Requests\RemovePointsFromContactRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Saloon\Http\Response;

class PointsResource extends Resource
{
    public function addPoints(int $contactId, int $pointDelta, ?array $attributes = null): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new AddPointsToContactRequest($contactId, $pointDelta, $attributes));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }

    public function subtractPoints(int $contactId, int $pointDelta, ?array $attributes = null): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new RemovePointsFromContactRequest($contactId, $pointDelta, $attributes));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }
}
