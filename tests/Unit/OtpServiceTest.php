<?php

namespace Tests\Unit;

use App\Services\OtpService;
use Tests\TestCase;

class OtpServiceTest extends TestCase
{
    public function test_generates_random_code_when_no_fixed_code_configured(): void
    {
        config()->set('mars.otp.fixed_code', null);

        $service = new OtpService;

        $code = $service->generate();

        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        $this->assertNotSame('123456', $code);
    }

    public function test_generates_fixed_code_when_configured(): void
    {
        config()->set('mars.otp.fixed_code', '123456');

        $service = new OtpService;

        $this->assertSame('123456', $service->generate());
        $this->assertTrue($service->verify('123456', $service->hash('000000')));
    }

    public function test_fixed_code_works_in_production(): void
    {
        config()->set('mars.otp.fixed_code', '123456');
        app()->detectEnvironment(fn () => 'production');

        $service = new OtpService;

        try {
            $this->assertSame('123456', $service->generate());
            $this->assertTrue($service->verify('123456', $service->hash('000000')));
        } finally {
            app()->detectEnvironment(fn () => 'testing');
        }
    }
}
