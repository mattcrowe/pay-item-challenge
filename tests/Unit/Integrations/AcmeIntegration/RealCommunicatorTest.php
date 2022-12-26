<?php

namespace Tests\Unit\Integrations\AcmeIntegration;

use PHPUnit\Framework\TestCase;

use App\Integrations\AcmeIntegration\RealCommunicator;

class RealCommunicatorTest extends TestCase
{
    /**
     * @covers \App\Integrations\AcmeIntegration\RealCommunicator::__construct
     */
    public function test___construct()
    {
        $communicator = new RealCommunicator(apiKey: "some key");

        $this->assertEquals($communicator->apiKey, "some key");
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\RealCommunicator::mapPayItem
     */
    public function test_mapPayItem()
    {
        $result = RealCommunicator::mapPayItem([
            'id' => 'some id',
            'employeeId' => 'some employee id',
            'hoursWorked' => 1.5,
            'payRate' => 10,
            'date' => '2022-11-01',
        ]);

        $this->assertEquals([
            'external_id' => 'some id',
            'user_external_id' => 'some employee id',
            'time_worked' => '01:30:00',
            'hourly_rate_in_cents' => 1000,
            'paid_at' => '2022-11-01',
        ], $result);
    }

    /**
     * @covers \App\Integrations\AcmeIntegration\RealCommunicator::test_mapPayItems
     */
    public function test_mapPayItems()
    {
        $rawPayItem = [
            'id' => 'some id',
            'employeeId' => 'some employee id',
            'hoursWorked' => 1.5,
            'payRate' => 10,
            'date' => '2022-11-01',
        ];

        $results = RealCommunicator::mapPayItems([$rawPayItem]);

        $this->assertCount(1, $results);

        $this->assertEquals([
            'external_id' => 'some id',
            'user_external_id' => 'some employee id',
            'time_worked' => '01:30:00',
            'hourly_rate_in_cents' => 1000,
            'paid_at' => '2022-11-01',
        ], $results[0]);
    }
}
