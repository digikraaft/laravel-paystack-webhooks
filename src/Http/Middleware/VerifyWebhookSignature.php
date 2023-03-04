<?php

namespace Digikraaft\PaystackWebhooks\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyWebhookSignature
{

    public function handle($request, Closure $next): \Illuminate\Http\Response
    {
        // validate that callback is coming from Paystack
        if ((! $request->isMethod('post')) || ! $request->header('HTTP_X_PAYSTACK_SIGNATURE', null)) {
            throw new AccessDeniedHttpException("Invalid Request");
        }

        $input = $request->getContent();
        $paystack_key = config('paystackwebhooks.secret', env('PAYSTACK_SECRET'));
        if ($request->header('HTTP_X_PAYSTACK_SIGNATURE') !== hash_hmac('sha512', $input, $paystack_key)) {
            throw new AccessDeniedHttpException("Access Denied");
        }

        return $next($request);
    }
}
