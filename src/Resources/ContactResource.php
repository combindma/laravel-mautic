<?php

namespace Combindma\Mautic\Resources;

use Combindma\Mautic\Requests\CreateContactRequest;
use Combindma\Mautic\Requests\DeleteContactRequest;
use Exception;
use Illuminate\Support\Facades\Log;
use Saloon\Http\Response;

class ContactResource extends Resource
{
    public function create(string $email, array $attributes = []): Response
    {
        try {
            return $this->connector->send(new CreateContactRequest($email, $attributes));
        } catch (Exception $e) {
            Log::error($e);
            exit();
        }
    }

    public function delete(int $id): Response
    {
        try {
            return $this->connector->send(new DeleteContactRequest($id));
        } catch (Exception $e) {
            Log::error($e);
            exit();
        }
    }
}
