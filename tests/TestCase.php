<?php

namespace Digikraaft\PaystackWebhooks\Tests;

use Digikraaft\PaystackWebhooks\PaystackWebhooksServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [PaystackWebhooksServiceProvider::class];
    }
}
