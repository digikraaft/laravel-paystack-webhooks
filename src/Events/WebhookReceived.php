<?php


namespace Digikraaft\PaystackWebhooks\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebhookReceived
{
    use Dispatchable, SerializesModels;


    public $payload;


    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}
