<?php

namespace Digikraaft\PaystackWebhooks\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyWebhookSignature
{

    public function handle($request, Closure $next)
    {
        // validate that callback is coming from Paystack
        if ((! $request->isMethod('post')) || ! $request->header('x-paystack-signature', null)) {
            throw new AccessDeniedHttpException("Invalid Request");
        }

        $input = $request->getContent();

        $paystackKey = config('paystackwebhooks.secret', env('PAYSTACK_SECRET'));

        if ($request->header('x-paystack-signature') !== hash_hmac('sha512', $input, $paystackKey)) {
            throw new AccessDeniedHttpException("Access Denied");
        }

        return $next($request);
    }
}
