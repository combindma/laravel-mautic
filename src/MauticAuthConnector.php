<?php

namespace Combindma\Mautic;

use Combindma\Mautic\Exceptions\UnableToHandleCallbackMauticUrl;
use Combindma\Mautic\Traits\MauticStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;

class MauticAuthConnector extends Connector
{
    use AuthorizationCodeGrant;
    use MauticStorage;

    private readonly string $baseUrl;

    private readonly string $clientKey;

    private readonly string $clientSecret;

    private readonly string $callback;

    public function __construct()
    {
        $this->baseUrl = config('mautic.baseUrl');
        $this->clientKey = config('mautic.clientKey');
        $this->clientSecret = config('mautic.clientSecret');
        $this->callback = config('mautic.callback');
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl.'/oauth/v2';
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId($this->clientKey)
            ->setClientSecret($this->clientSecret)
            ->setRedirectUri($this->callback)
            ->setAuthorizeEndpoint('authorize')
            ->setTokenEndpoint('token');
    }

    public function authorize(): RedirectResponse
    {
        Session::put('mautic.oauth_state', $this->getState());
        $authorizationUrl = $this->getAuthorizationUrl(additionalQueryParameters: [
            'response_type' => 'code',
            'grant_type' => 'authorization_code',
        ]);

        return Redirect::away($authorizationUrl);
    }

    public function requestToken(Request $request): void
    {
        if (! $request->input('code') && ! $request->input('state')) {
            throw new UnableToHandleCallbackMauticUrl();
        }
        $oauth_session = $request->session()->pull('mautic');
        $authenticator = $this->getAccessToken($request->input('code'), $request->input('state'), $oauth_session['oauth_state']);
        $this->storeToken($authenticator);
    }

    public function updateAuthenticator(AccessTokenAuthenticator $authenticator)
    {
        $this->storeToken($authenticator);
    }
}
