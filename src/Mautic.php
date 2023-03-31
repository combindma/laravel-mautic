<?php

namespace Combindma\Mautic;

use Combindma\Mautic\Exceptions\UnableToGetMauticTokensException;
use Combindma\Mautic\Exceptions\UnableToHandleCallbackMauticUrl;
use Combindma\Mautic\Exceptions\UnableToStoreMauticDataException;
use Combindma\Mautic\Exceptions\UnauthorizedStateIsReturned;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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

    private readonly string $apiUrl;

    private string $endpoint;

    private readonly string $fileName;

    private bool $apiEnabled;

    public function __construct()
    {
        $this->version = config('mautic.version');
        $this->baseUrl = config('mautic.baseUrl');
        $this->clientKey = config('mautic.clientKey');
        $this->clientSecret = config('mautic.clientSecret');
        $this->callback = config('mautic.callback');
        $this->username = config('mautic.username');
        $this->password = config('mautic.password');
        $this->apiUrl = $this->baseUrl.'/oauth/v2/';
        $this->endpoint = $this->baseUrl.'/api/';
        $this->fileName = config('mautic.fileName');
        $this->apiEnabled = config('mautic.apiEnabled');
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

    public function disable(): void
    {
        $this->apiEnabled = false;
    }

    public function enable(): void
    {
        $this->apiEnabled = true;
    }

    //Obtain Authorization Code
    public function authorize(): \Illuminate\Http\RedirectResponse
    {
        //The state is recommended to prevent CSRF attacks.
        $state = md5(time().mt_rand());
        //It should be a uniquely generated string and stored locally in session. to be compared with the returned value.
        Session::put('mautic.oauth_state', $state);
        $authUrl = $this->apiUrl.'authorize'.'?client_id='.$this->clientKey.'&redirect_uri='.urlencode($this->callback).'&state='.$state.'&response_type=code&grant_type=authorization_code';
        //Redirect the user to the authorize endpoint oauth/v2/authorize:
        return Redirect::away($authUrl);
    }

    //Replace with an Access Token
    public function requestToken(Request $request): void
    {
        $oauth_session = $request->session()->pull('mautic');

        if (! $request->input('code') && ! $request->input('state')) {
            throw new UnableToHandleCallbackMauticUrl();
        }

        if (empty($oauth_session)) {
            throw new UnauthorizedStateIsReturned();
        }

        if ($request->input('state') != $oauth_session['oauth_state']) {
            throw new UnauthorizedStateIsReturned();
        }

        $response = Http::post($this->apiUrl.'token', [
            'client_id' => $this->clientKey,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->callback,
            'grant_type' => 'authorization_code',
            'code' => $request->input('code'),
        ]);

        if ($response->failed()) {
            Log::error('Mautic error: '.$response->body());
        }

        $this->storeAccessToken($response->json());
    }

    //Refresh Tokens
    private function refreshToken(): array
    {
        $response = Http::post($this->apiUrl.'token', [
            'client_id' => $this->clientKey,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->getAccessToken()['refresh_token'],
            'redirect_uri' => $this->callback,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->failed()) {
            Log::error('Mautic error: '.$response->body());
            throw new Exception($response->body());
        }

        $this->storeAccessToken($response->json());

        return $response->json();
    }

    //Store mautic data in local storage
    private function storeAccessToken(array $accessToken): void
    {
        if (! Storage::disk('local')->put(config('mautic.fileName', 'mautic.json'), json_encode($accessToken))) {
            throw new UnableToStoreMauticDataException();
        }
    }

    //Get mautic data from local storage
    /*
     * access_token
     * expires_in
     * refresh_token
     */
    private function getAccessToken(): array
    {
        if ($accessToken = Storage::disk('local')->json($this->fileName)) {
            return $accessToken;
        }

        throw new UnableToGetMauticTokensException();
    }

    //Check AccessToken Expiration Time
    private function checkExpirationTime($expireTimestamp): bool
    {
        return time() > time() + $expireTimestamp;
    }
}
