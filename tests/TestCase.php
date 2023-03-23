<?php

namespace Combindma\Mautic\Tests;

use Combindma\Mautic\MauticServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            MauticServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('app.locale', 'en');
        config()->set('mautic', [
            'version' => 'OAuth2',
            'baseUrl' => 'http://mautic.domain.com',
            'clientKey' => 'CLIENTKEY',
            'clientSecret' => 'CLIENTSECRET',
            'callback' => 'http://localhost/integration/mautic',
            'username' => 'my-username',
            'password' => 'MY_PASSWORD',
            'apiEnabled' => true,
            'fileName' => 'mautic.json',
        ]);
    }
}
