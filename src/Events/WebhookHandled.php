<?php


namespace Digikraaft\PaystackWebhooks\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebhookHandled
{
    use Dispatchable, SerializesModels;


    public array $payload;


    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
