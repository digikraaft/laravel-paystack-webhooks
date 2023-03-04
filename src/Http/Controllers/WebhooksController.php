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

    public function __construct()
    {
        if (config('paystackwebhooks.secret', env('PAYSTACK_SECRET'))) {
            $this->middleware(VerifyWebhookSignature::class);
        }
    }


    public function handleWebhook(Request $request): \Symfony\Component\HttpFoundation\Response
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


    protected function successMethod(array $parameters = []): \Symfony\Component\HttpFoundation\Response
    {
        return new Response('Webhook Handled', 200);
    }


    protected function missingMethod(array $parameters = []): \Symfony\Component\HttpFoundation\Response
    {
        return new Response;
    }
}
