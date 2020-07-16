# Handle Paystack Webhooks in a Laravel application
![tests](https://github.com/digikraaft/laravel-paystack-webhooks/workflows/tests/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

[Paystack](https://paystack.com/) can notify your application about various events via webhooks. This package can
help you handle those webhooks. It will automatically verify all incoming requests and ensure they are coming
from Paystack. By default, a route that points to this package's webhook controller is configured through the service provider.

Please note that this package will NOT handle what should be done after the request has been validated. You
should still write the code for that.

Find out more details about [Paystack's Event here](https://paystack.com/docs/payments/webhooks/#supported-events)

# Installation

You can install the package via composer:

```bash
composer require digikraaft/laravel-paystack-webhooks
```
#### Configuration File
The Laravel Paystack Webhooks package comes with a configuration file, here is the content of the file:
```php
return [

    /*
    |--------------------------------------------------------------------------
    | Paystack Keys
    |--------------------------------------------------------------------------
    |
    | The Paystack publishable key and secret key. You can get these from your Paystack dashboard.
    |
    */

    'public_key' => env('PAYSTACK_PUBLIC_KEY'),

    'secret' => env('PAYSTACK_SECRET'),

    /*
   |--------------------------------------------------------------------------
   | Webhooks Path
   |--------------------------------------------------------------------------
   |
   | This is the base URI path where webhooks will be handled from.
   |
   | This should correspond to the webhooks URL set in your Paystack dashboard:
   | https://dashboard.paystack.com/#/settings/developer.
   |
   | If your webhook URL is https://domain.com/paystack/webhook/ then you should simply enter paystack here.
   |
   | Remember to also add this as an exception in your VerifyCsrfToken middleware.
   |
   | See the demo application linked on github to help you get started.
   |
   */
    'webhook_path' => env('PAYSTACK_WEBHOOK_PATH', 'paystack'),

];
```
You can publish this config file with the following commands:
```bash
php artisan vendor:publish --provider="Digikraaft\PaystackWebhooks\PaystackWebhooksServiceProvider" --tag="config"
```

### API Keys
You should configure your Paystack keys in your .env file. 
You can get your Paystack API keys from the Paystack dashboard.
```dotenv
PAYSTACK_PUBLIC_KEY=your-paystack-public-key
PAYSTACK_SECRET=your-paystack-secret
```

# Handling Paystack Webhooks
[Paystack](https://paystack.com/) can notify your application about various events via webhooks. This package can
help you handle those webhooks. It will automatically verify all incoming requests and ensure they are coming
from Paystack. By default, a route that points to this package's webhook controller is configured through the service provider.

Please note that this package will NOT handle what should be done after the request has been validated. You
should still write the code for that. All you need do is to extend the controller in order to handle 
any webhook event you like. For example, if you wish to handle the `charge.success` event, 
you should add a `handleChargeSuccess` method to the controller:

```php
<?php

namespace App\Http\Controllers;

use Digikraaft\PaystackWebhooks\Http\Controllers\WebhooksController as PaystackWebhooksController;

class WebhookController extends PaystackWebhooksController
{
    /**
     * Handle charge success.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleChargeSuccess($payload)
    {
        // Handle The Event
    }
}
```

To ensure your application can handle Paystack webhooks, be sure to configure the webhook URL in the Paystack dashboard. 
By default, this package's webhook controller listens to the `/paystack/webhook`.

Next, define a route to your controller within your `routes/web.php` file.

```
Route::post(
    'paystack/webhook',
    '\App\Http\Controllers\WebhookController@handleWebhook'
);
```

### Webhooks & CSRF Protection
Since Paystack webhooks need to bypass Laravel's CSRF protection, be sure to list the URI as an exception in your 
`VerifyCsrfToken` middleware or list the route outside of the `web` middleware group:
```
protected $except = [
    'paystack/*',
];
```

This package emits a `Digikraaft\PaystackWebhooks\Events\WebhookReceived` event when a webhook is received, 
and a `Digikraaft\PaystackWebhooks\Events\WebhookHandled` event when a webhook was handled by you. 
Both events contain the full payload of the Paystack webhook.

You can find details about Paystack events [here](https://paystack.com/docs/payments/webhooks/#supported-events)

### Verifying Webhook Signatures
For convenience, this package automatically includes a middleware which validates that the incoming Paystack webhook request is valid.

## Testing
Use the command below to run your tests:
``` bash
composer test
```
## More Good Stuff
Check [here](https://github.com/digikraaft) for more awesome free stuff!

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security related issues, please email dev@digitalkraaft.com instead of using the issue tracker.

## Credits
- [Tim Oladoyinbo](https://github.com/timoladoyinbo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
