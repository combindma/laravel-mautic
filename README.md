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
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-mautic-views"
```

## Mautic Setup
The API must be enabled in Mautic.

Within Mautic, go to the Configuration page (located in the Settings menu) and under API Settings enable Mautic's API.

After saving the configuration, go to the API Credentials page (located in the Settings menu) and create a new client. 

Enter the callback/redirect URI (Should be `https://your-domain.com/login/mautic/callback`). Click Apply, then copy the Client ID and Client Secret to the .env file.

This is an example of .env file:

```
MAUTIC_BASE_URL="https://your-domain.com"
MAUTIC_PUBLIC_KEY="XXXXXXXXXXXXXXXX"
MAUTIC_SECRET_KEY="XXXXXXXXXX"
MAUTIC_CALLBACK="https://your-domain.com/login/mautic/callback"
```

## Usage

```php
$mautic = new Combindma\Mautic();
echo $mautic->echoPhrase('Hello, Combindma!');
```

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
