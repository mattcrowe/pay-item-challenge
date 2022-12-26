<?php

namespace Tests\Unit\Integrations\AcmeIntegration;

use PHPUnit\Framework\TestCase;

use App\Integrations\AcmeIntegration\Gateway;
use App\Integrations\AcmeIntegration\FakeCommunicator;
use App\Integrations\AcmeIntegration\RealCommunicator;

class GatewayTest extends TestCase
{
    /**
     * @covers \App\Integrations\AcmeIntegration\Gateway::__construct
     */
    public function test___construct()
    {
        $gateway = new Gateway(apiKey: "some key", strategy: "some strategy");

        $this->assertEquals($gateway->apiKey, "some key");
        $this->assertEquals($gateway->strategy, "some strategy");
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\Gateway::up
     */
    public function test_invalidStrategy()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(Gateway::INVALID_STRATEGY_MESSAGE);

        $gateway = new Gateway(apiKey: "", strategy: "invalid strategy");
        $gateway->up();
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\Gateway::up
     */
    public function test_fakeStrategy()
    {
        $gateway = new Gateway(apiKey: "", strategy: Gateway::FAKE);
        $communicator = $gateway->up();

        $this->assertInstanceOf(FakeCommunicator::class, $communicator);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\Gateway::up
     */
    public function test_realStrategy()
    {
        $gateway = new Gateway(apiKey: "", strategy: Gateway::REAL);
        $communicator = $gateway->up();

        $this->assertInstanceOf(RealCommunicator::class, $communicator);
    }
}
