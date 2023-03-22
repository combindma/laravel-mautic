<?php

namespace Combindma\Mautic;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mautic\Auth\ApiAuth;

class Mautic
{
    private string $baseUrl;

    private string $clientKey;

    private string $clientSecret;

    private string $callback;

    private bool $apiEnabled;

    private string $fileName;

    private array $settings;

    public function __construct()
    {
        $this->baseUrl = config('mautic.baseUrl');
        $this->clientKey = config('mautic.clientKey');
        $this->clientSecret = config('mautic.clientSecret');
        $this->callback = config('mautic.callback');
        $this->fileName = config('mautic.fileName');
        $this->settings = [
            'baseUrl' => $this->baseUrl,
            'version' => 'OAuth2',
            'clientKey' => $this->clientKey,
            'clientSecret' => $this->clientSecret,
            'callback' => $this->callback,
        ];
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getCallback(): string
    {
        return $this->callback;
    }

    public function isApiEnabled(): bool
    {
        return $this->apiEnabled;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    public function disable(): void
    {
        $this->apiEnabled = false;
    }

    public function enable(): void
    {
        $this->apiEnabled = true;
    }

    /*
     * Initiate process for obtaining an access token; this will redirect the user to the $authorizationUrl
     * and/or set the access_tokens when the user is redirected back after granting authorization
     * If the access token is expired, and a refresh token is set above, then a new access token will be requested
     * */
    public function getAccessTokenData(): ?array
    {
        session_start();

        $mautic = json_decode(Storage::disk('local')->get($this->getFileName()));
        if ($mautic) {
            $this->setSettings([
                'accessToken' => $mautic->access_token,
                'accessTokenExpires' => $mautic->expires,
                'refreshToken' => $mautic->refresh_token,
            ]);
        }
        // Initiate the auth object
        $auth = (new ApiAuth())->newAuth($this->settings);
        try {
            if ($auth->validateAccessToken()) {
                // Obtain the access token returned; call accessTokenUpdated() to catch if the token was updated via a refresh token
                // $accessTokenData will have the following keys: access_token, expires, token_type, refresh_token
                if ($auth->accessTokenUpdated()) {
                    return $auth->getAccessTokenData();
                }
            }
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }
}
