<?php

namespace Combindma\Mautic\Traits;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait MauticApi
{
    public function buildRequest(): ?PendingRequest
    {
        if (! $this->isApiEnabled()) {
            return null;
        }

        if ($this->version === 'BasicAuth') {
            if (empty($this->username) || empty($this->password)) {
                throw new Exception('If you plan to use BasicAuth version yous should provide a username and password.');
            }

            return Http::withBasicAuth($this->username, $this->password);
        }

        $accessTokens = $this->getAccessToken();

        if ($this->checkExpirationTime($accessTokens['expires_in'])) {
            $accessTokens = $this->refreshToken();
        }

        return Http::withToken($accessTokens['access_token']);
    }

    public function subscribe(string $email, array $attributes = []): Response|null
    {
        try {
            return $this->buildRequest()?->post($this->endpoint.'contacts/new', array_merge([
                'email' => $email,
                'ipAddress' => request()->ip(),
            ], $attributes));
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }

    public function unsubscribe(int|string $id): Response|null
    {
        try {
            return $this->buildRequest()?->delete($this->endpoint.'contacts/'.$id.'/delete');
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }
}
