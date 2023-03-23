# Mautic API for Laravel applications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/combindma/laravel-mautic.svg?style=flat-square)](https://packagist.org/packages/combindma/laravel-mautic)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/combindma/laravel-mautic/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/combindma/laravel-mautic/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/combindma/laravel-mautic/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/combindma/laravel-mautic/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/combindma/laravel-mautic.svg?style=flat-square)](https://packagist.org/packages/combindma/laravel-mautic)

Free and Open Source Marketing Automation APIFree and Open Source Marketing Automation API

## Installation

You can install the package via composer:

```bash
composer require combindma/laravel-mautic
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-mautic-config"
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Auth Type
    |--------------------------------------------------------------------------
    | Version of the auth can be OAuth2 or BasicAuth. OAuth2 is the default value.
    |
    */
    'version' => 'OAuth2', //or BasicAuth

    /*
     * Base URL of the Mautic instance
     */
    'baseUrl' => env('MAUTIC_BASE_URL'),

    /*
     * Client/Consumer key from Mautic
     */
    'clientKey' => env('MAUTIC_PUBLIC_KEY'),

    /*
     * Client/Consumer secret key from Mautic
     */
    'clientSecret' => env('MAUTIC_SECRET_KEY'),

    /*
     * Redirect URI/Callback URI for this script
     */
    'callback' => env('MAUTIC_CALLBACK'),

    /*
    |--------------------------------------------------------------------------
    | Mautic App Credentials
    |--------------------------------------------------------------------------
    |
    | This is used in case of BasicAuth
    |
    */
    'username' => env('MAUTIC_USERNAME'),

    'password' => env('MAUTIC_PASSWORD'),

    /*
    * Enable or disable Mautic. Useful for local development when running tests.
    */
    'apiEnabled' => env('MAUTIC_ENABLED', false),

    /*
    * Filename to use when storing mautic access token. Must be a json File
    */
    'fileName' => 'mautic.json',
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-mautic-views"
```

## Authorization
This Library only supports `OAuth2` and `BasicAuth` Authentication.
For OAuth2 you need to create a `OAuth2` client in order to use the api.

## OAuth2 Mautic Setup
The API must be enabled in Mautic.
Within Mautic, go to the Configuration page (located in the Settings menu) and under API Settings enable Mautic's API.
After saving the configuration, go to the API Credentials page (located in the Settings menu) and create a new client.
Enter the callback/redirect URI (Should be `https://your-domain.com/integration/mautic`). Click Apply, then copy the Client ID and Client Secret to the .env file.
This is an example of .env file:

```
MAUTIC_BASE_URL="https://your-domain.com"
MAUTIC_PUBLIC_KEY="XXXXXXXXXXXXXXXX"
MAUTIC_SECRET_KEY="XXXXXXXXXX"
MAUTIC_CALLBACK="https://your-domain.com/integration/mautic"
MAUTIC_USERNAME=email@email.com
MAUTIC_PASSWORD=password
```

## BasicAuth Mautic Setup
You need to add your `username` and `password` for BasicAuth:
```
MAUTIC_USERNAME=email@email.com
MAUTIC_PASSWORD=password
```

## Registering Application (Only OAuth2 Authentication)
In order to register you application with mautic ping this url this is one time registration.
```url
https://your-domain.com/integration/mautic
```

## Usage

```php
use Combindma\Mautic\Facades\Mautic;

Mautic::subscribe('email@gmail.com');

//or
$params = array(
    'firstname' => 'bullet',
    'lastname'  => 'proof',
);
Mautic::subscribe('email@gmail.com', $params);

//create ContactApi to use it accordingly to Mautic documentation
$contactApi = Mautic::contactApi();
$contactApi->delete($id);
```

Please refer to [Documentation](https://developer.mautic.org).
for all customizable parameters.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Combind](https://github.com/combindma)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
