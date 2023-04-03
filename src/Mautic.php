<?php

namespace Combindma\Mautic;

use Combindma\Mautic\Resources\ContactResource;
use Combindma\Mautic\Resources\SegmentResource;
use Combindma\Mautic\Traits\MauticStorage;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class Mautic extends Connector
{
    use MauticStorage;
    use AlwaysThrowOnErrors;

    private readonly string $version;

    private readonly string $baseUrl;

    private readonly string $username;

    private readonly string $password;

    private bool $apiEnabled;

    public function __construct()
    {
        $this->version = config('mautic.version');
        $this->baseUrl = config('mautic.baseUrl');
        $this->username = config('mautic.username');
        $this->password = config('mautic.password');
        $this->apiEnabled = config('mautic.apiEnabled');
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl.'/api';
    }

    public function contacts(): ContactResource
    {
        return new ContactResource($this);
    }

    public function segments(): SegmentResource
    {
        return new SegmentResource($this);
    }

    protected function defaultAuth(): ?Authenticator
    {
        if ($this->version === 'BasicAuth') {
            return new BasicAuthenticator($this->username, $this->password);
        }

        $authenticator = $this->getToken();

        if ($authenticator->hasExpired() === true) {
            $authConnector = new MauticAuthConnector;
            $authenticator = $authConnector->refreshAccessToken($authenticator);
            $authConnector->updateAuthenticator($authenticator);
        }

        return new TokenAuthenticator($authenticator->getAccessToken());
    }

    public function isApiDisabled(): bool
    {
        return ! $this->apiEnabled;
    }
}
