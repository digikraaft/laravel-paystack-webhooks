<?php


namespace Digikraaft\PaystackWebhooks\Http\Controllers;

use Digikraaft\PaystackWebhooks\Events\WebhookHandled;
use Digikraaft\PaystackWebhooks\Events\WebhookReceived;
use Digikraaft\PaystackWebhooks\Http\Middleware\VerifyWebhookSignature;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class WebhooksController extends Controller
{
    /**
     * Create a new WebhooksController instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('paystackwebhooks.secret', env('PAYSTACK_SECRET'))) {
            $this->middleware(VerifyWebhookSignature::class);
        }
    }

    /**
     * Handle Paystack webhooks call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle'.Str::studly(str_replace('.', '_', $payload['event']));

        WebhookReceived::dispatch($payload);

        if (method_exists($this, $method)) {
            $response = $this->{$method}($payload);

            WebhookHandled::dispatch($payload);

            return $response;
        }

        return $this->missingMethod();
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function successMethod($parameters = [])
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function missingMethod($parameters = [])
    {
        return new Response;
    }
}
