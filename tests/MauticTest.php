<?php

use Combindma\Mautic\Mautic;

it('can test if config file are set', function () {
    $mautic = new Mautic();
    expect($mautic->getBaseUrl())->toBe('http://mautic.domain.com')
        ->and($mautic->getClientKey())->toBe('CLIENTKEY')
        ->and($mautic->getClientSecret())->toBe('CLIENTSECRET')
        ->and($mautic->getCallback())->toBe('http://localhost/integration/mautic')
        ->and($mautic->getFileName())->toBe('mautic.json')
        ->and($mautic->getUsername())->toBe('my-username')
        ->and($mautic->getPassword())->toBe('MY_PASSWORD')
        ->and($mautic->isApiEnabled())->toBeTrue();
});

it('can set construct settings according to the config file', function () {
    $mautic = new Mautic();
    expect($mautic->getSettings())->toBe([
        'baseUrl' => 'http://mautic.domain.com',
        'version' => 'OAuth2',
        'clientKey' => 'CLIENTKEY',
        'clientSecret' => 'CLIENTSECRET',
        'callback' => 'http://localhost/integration/mautic',
    ]);
});

it('can set construct settings with BasicAuth authentification version', function () {
    config()->set('mautic.version', 'BasicAuth');
    $mautic = new Mautic();
    expect($mautic->getSettings())->toBe([
        'userName' => 'my-username',
        'password' => 'MY_PASSWORD',
    ]);
});

it('can set new settings attributes', function () {
    $mautic = new Mautic();
    $mautic->setSettings(['key' => 'value']);
    expect($mautic->getSettings())->toBe([
        'baseUrl' => $mautic->getBaseUrl(),
        'version' => 'OAuth2',
        'clientKey' => $mautic->getClientKey(),
        'clientSecret' => $mautic->getClientSecret(),
        'callback' => $mautic->getCallback(),
        'key' => 'value',
    ]);
});

it('can disable api on the fly', function () {
    config()->set('mautic.apiEnabled', true);
    $mautic = new Mautic();
    $mautic->disable();
    expect($mautic->isApiEnabled())->toBeFalse();
});

it('can enable api on the fly if disabled', function () {
    config()->set('mautic.apiEnabled', false);
    $mautic = new Mautic();
    $mautic->enable();
    expect($mautic->isApiEnabled())->toBeTrue();
});
