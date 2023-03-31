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
