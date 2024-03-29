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
    'version' => env('MAUTIC_VERSION', 'OAuth2'), //or BasicAuth

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

Enter the callback/redirect URI (Should be `https://your-domain.com/integration/mautic/callback`). Click Apply, then copy the Client ID and Client Secret to the .env file.

This is an example of .env file:

```
MAUTIC_VERSION="OAuth2"
MAUTIC_BASE_URL=https://mautic-domain.com
MAUTIC_PUBLIC_KEY=XXXXXXXXXXXXXXXX
MAUTIC_SECRET_KEY=XXXXXXXXXX
MAUTIC_CALLBACK=https://your-domain.com/integration/mautic/callback
MAUTIC_ENABLED=true
```

## BasicAuth Mautic Setup
You need to add your `username` and `password` in .env file for BasicAuth:
```
MAUTIC_VERSION="BasicAuth"
MAUTIC_BASE_URL=https://mautic-domain.com
MAUTIC_USERNAME=email@email.com
MAUTIC_PASSWORD=password
MAUTIC_ENABLED=true
```

## Registering Application (Only OAuth2 Authentication)
In order to register you application with mautic ping this url one time to register your application.
```url
https://your-domain.com/integration/mautic
```

## Usage

#### Managing Contacts
When working with Contact API, the IpAddress and lastActive parameters are added by default, so that you don't have to include them.
```php
use Combindma\Mautic\Facades\Mautic;

//Create a new contact
Mautic::contacts()->create('email@gmail.com');

//with $parameters
$params = array(
    'firstname' => 'bullet',
    'lastname'  => 'proof',
);
Mautic::contacts()->create('email@gmail.com', $params);

//Edit a given contact
Mautic::contacts()->edit($contactId, $params);

//Delete a contact
Mautic::contacts()->delete(567);//567 is the contact ID, change it to your needs
```

#### Managing Contacts with segment
```php
use Combindma\Mautic\Facades\Mautic;

//Add contact to a segment
Mautic::segments()->addContact($segmentId, $contactId);

//Create a contact and add it to a segment
$response = Mautic::contacts()->create(strtolower($request->input('email')));
if ($response && !$response->failed())
{
    $contactId = $response->object()->contact->id;
    Mautic::segments()->addContact(4, $contactId);//4 is the segment ID, change it to your needs
}

//Remove a contact from a given segment
Mautic::segments()->removeContact($segmentId, $contactId);
```

#### Managing Contacts with UTM Tags
```php
use Combindma\Mautic\Facades\Mautic;

//Add UTM tags to a given contact
$data = array(
    'utm_campaign' => 'apicampaign',
    'utm_source'   => 'fb',
    'utm_medium'   => 'social',
    'utm_content'  => 'fbad',
    'utm_term'     => 'mautic api',
    'useragent'    => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0',
    'url'          => '/product/fbad01/',
    'referer'      => 'https://google.com/q=mautic+api',
    'query'        => ['cid'=>'abc','cond'=>'new'], // or as string with "cid=abc&cond=new"
    'remotehost'   => 'example.com',
    'lastActive'   => '2017-01-17T00:30:08+00:00'
 );
 
Mautic::utmTags()->addUtmTag($contactId, $data);

//Remove a given UTM Tag from contact
Mautic::utmTags()->removeUtmTag($utmId, $contactId);
```

#### Managing Contacts points
```php
use Combindma\Mautic\Facades\Mautic;

//Add contact points
$data = array(
    'eventName' => 'Score via api',
    'actionName' => 'Adding',
 );
 
Mautic::points()->addPoints($contactId, 10, $data);//$data is optional

//Subtract contact points
$data = array(
    'eventname' => 'Score via api',
    'actionname' => 'Subtracting',
 );
Mautic::points()->subtractPoints($contactId, 10, $data);//$data is optional
```

#### Macrobale
Communicating with the API can become a repetitive process. that's why we made this package macroable.

```php
use Combindma\Mautic\Facades\Mautic;

//include this in your macrobale file
Mautic::macro('subscribe', function(string $email) {
    $response = Mautic::contacts()->create(strtolower($request->input('email')));
    if ($response && !$response->failed())
    {
        $contactId = $response->object()->contact->id;
        Mautic::segments()->addContact(4, $contactId);//4 is the segment ID, change it to your needs
    }
});

//In your controller, you only need to call your method
Mautic::sbscribe('email@email.com');
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
