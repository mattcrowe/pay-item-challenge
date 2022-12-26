<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

use App\Helpers\PayItemHelper;

class PayItemHelperTest extends TestCase
{
    /**
     * @covers \App\Helpers\PayItemHelper::calcAmountToPayInCents
     */
    public function test__calcAmountToPayInCents_0hours()
    {
        $timeWorked = 0;
        $hourlyRateInCents = 1;

        $amount = PayItemHelper::calcAmountToPayInCents($timeWorked, $hourlyRateInCents);

        $this->assertEquals(0, $amount);
    } 
    
    /**
     * @covers \App\Helpers\PayItemHelper::calcAmountToPayInCents
     */
    public function test__calcAmountToPayInCents_0rate()
    {
        $timeWorked = 0;
        $hourlyRateInCents = 0;        

        $amount = PayItemHelper::calcAmountToPayInCents($timeWorked, $hourlyRateInCents);

        $this->assertEquals(0, $amount);
    } 

    /**
     * @covers \App\Helpers\PayItemHelper::calcAmountToPayInCents
     */
    public function test__calcAmountToPayInCents_nullDeduction()
    {
        $sets = [
            ["08:30:00", 12.5 * 100, 3188],
            ["10:00:00", 10 * 100, 3000],
        ];

        foreach($sets as $set) {
            list($timeWorked, $hourlyRateInCents, $expectedAmountInCents) = $set;

            $actualAmountInCents = PayItemHelper::calcAmountToPayInCents($timeWorked, $hourlyRateInCents);

            $this->assertEquals($expectedAmountInCents, $actualAmountInCents);
        }
    }
    
    /**
     * @covers \App\Helpers\PayItemHelper::calcAmountToPayInCents
     */
    public function test__calcAmountToPayInCents_withDeduction()
    {
        $sets = [
            ["08:30:00", 12.5 * 100, 50, 5313],
            ["08:30:00", 12.5 * 100, 30, 3188],
            ["08:30:00", 12.5 * 100, 10, 1063],
        ];

        foreach($sets as $set) {
            list($timeWorked, $hourlyRateInCents, $deduction, $expectedAmountInCents) = $set;

            $actualAmountInCents = PayItemHelper::calcAmountToPayInCents($timeWorked, $hourlyRateInCents, $deduction);

            $this->assertEquals($expectedAmountInCents, $actualAmountInCents);
        }
    }
}
