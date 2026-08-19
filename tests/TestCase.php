<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $adminSubdomain = config('app.admin_subdomain');
        $domain = config('app.domain');
        $adminUrl = "http://{$adminSubdomain}.{$domain}";
        
        $this->app['config']->set('app.url', $adminUrl);
        $this->app['config']->set('session.domain', ".{$domain}");
        $this->baseUrl = $adminUrl;
    }
}
