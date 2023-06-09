<?php

namespace Combindma\Mautic\Resources;

use Combindma\Mautic\Requests\CreateContactRequest;
use Combindma\Mautic\Requests\DeleteContactRequest;
use Combindma\Mautic\Requests\EditContactRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Saloon\Http\Response;

class ContactResource extends Resource
{
    public function create(string $email, array $attributes = []): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new CreateContactRequest($email, $attributes));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }

    public function edit(int $contactId, array $attributes): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new EditContactRequest($contactId, $attributes));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }

    public function delete(int $id): ?Response
    {
        if ($this->connector->isApiDisabled()) {
            return null;
        }

        try {
            return $this->connector->send(new DeleteContactRequest($id));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }
}
