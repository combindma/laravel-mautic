<?php

namespace Combindma\Mautic;

use Combindma\Mautic\Exceptions\UnableToStoreMauticDataException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mautic\Api\Api;
use Mautic\Auth\ApiAuth;
use Mautic\Exception\ContextNotFoundException;
use Mautic\MauticApi;

class Mautic
{
    use Traits\MauticApi;

    private string $version;

    private readonly string $baseUrl;

    private readonly string $clientKey;

    private readonly string $clientSecret;

    private readonly string $callback;

    private readonly string $username;

    private readonly string $password;

    private bool $apiEnabled;

    private readonly string $fileName;

    private array $settings;

    public function __construct()
    {
        $this->version = config('mautic.version');
        $this->baseUrl = config('mautic.baseUrl');
        $this->clientKey = config('mautic.clientKey');
        $this->clientSecret = config('mautic.clientSecret');
        $this->callback = config('mautic.callback');
        $this->username = config('mautic.username');
        $this->password = config('mautic.password');
        $this->fileName = config('mautic.fileName');
        $this->apiEnabled = config('mautic.apiEnabled');
        $this->initiateSettings();
    }

    public function getVersion(): string
    {
        return $this->version;
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

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isApiEnabled(): bool
    {
        return $this->apiEnabled;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function initiateSettings(): void
    {
        if ($this->version === 'OAuth2') {
            $this->settings = [
                'baseUrl' => $this->baseUrl,
                'version' => 'OAuth2',
                'clientKey' => $this->clientKey,
                'clientSecret' => $this->clientSecret,
                'callback' => $this->callback,
            ];
        } else {
            $this->settings = [
                'userName' => $this->getUsername(),
                'password' => $this->getPassword(),
            ];
        }
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

    protected function getMauticData()
    {
        return json_decode(Storage::disk('local')->get($this->getFileName()));
    }

    /**
     * @throws UnableToStoreMauticDataException
     * @throws ContextNotFoundException
     */
    public function contactApi(): ?Api
    {
        if (! $this->isApiEnabled()) {
            return null;
        }

        if ($this->getVersion() === 'OAuth2') {
            $mautic = $this->authorizeApplication(false);
            $this->setSettings([
                'accessToken' => $mautic['access_token'],
                'accessTokenExpires' => $mautic['expires'],
                'refreshToken' => $mautic['refresh_token'],
            ]);
            $auth = (new ApiAuth())->newAuth($this->settings);
        } else {
            $initAuth = new ApiAuth();
            $auth = $initAuth->newAuth($this->settings, 'BasicAuth');
        }

        return (new MauticApi())->newApi('contacts', $auth, $this->getBaseUrl());
    }

    /*
     * Handle authorization request
     * */
    public function authorizeApplication($redirect = true): ?array
    {
        $mauticData = $this->getAccessTokenData($redirect);
        // The file could not be written to disk...
        if (! Storage::disk('local')->put(config('mautic.fileName'), json_encode($mauticData))) {
            throw new UnableToStoreMauticDataException('Unable to store Mautic data.');
        }

        return (array) $mauticData;
    }

    protected function getAccessTokenData($redirect = true): array
    {
        session_start();

        $mautic = $this->getMauticData();

        if (! empty($mautic)) {
            $this->setSettings([
                'accessToken' => $mautic->access_token,
                'accessTokenExpires' => $mautic->expires,
                'refreshToken' => $mautic->refresh_token,
            ]);
        }
        // Initiate the auth object
        $auth = (new ApiAuth())->newAuth($this->settings);

        try {
            //$auth->validateAccessToken() will redirect user to Mautic where he can authorize the app.
            if ($auth->validateAccessToken($redirect)) {
                // call accessTokenUpdated() to catch if the token was updated via a refresh token
                if ($auth->accessTokenUpdated()) {
                    // $accessTokenData will have the following keys: access_token, expires, token_type, refresh_token
                    return $auth->getAccessTokenData();
                }
            }
        } catch (Exception $e) {
            Log::error($e);
        }

        //This app is already authorized.
        return (array) $mautic;
    }
}
