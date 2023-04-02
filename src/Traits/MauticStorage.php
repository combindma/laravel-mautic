<?php

namespace Combindma\Mautic\Traits;

use Combindma\Mautic\Exceptions\UnableToGetMauticTokensException;
use Combindma\Mautic\Exceptions\UnableToStoreMauticDataException;
use Illuminate\Support\Facades\Storage;
use Saloon\Http\Auth\AccessTokenAuthenticator;

trait MauticStorage
{
    //Store mautic data in local storage
    public function storeToken(AccessTokenAuthenticator $authenticator): void
    {
        if (! Storage::disk('local')->put(config('mautic.fileName', 'mautic.json'), json_encode($authenticator->serialize()))) {
            throw new UnableToStoreMauticDataException();
        }
    }

    //Get mautic data from local storage
    public function getToken(): AccessTokenAuthenticator
    {
        if ($accessToken = Storage::disk('local')->get(config('mautic.fileName', 'mautic.json'))) {
            return AccessTokenAuthenticator::unserialize(json_decode($accessToken));
        }

        throw new UnableToGetMauticTokensException();
    }
}
