<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

use App\Helpers\TimeHelper;

class TimeHelperTest extends TestCase
{    
    public function test_timeToSeconds()
    {
        $kvs = [
            [NULL, 0],
            [0, 0],
            ["", 0],
            ["00:00:01", 1],
            ["00:01:00", 60],
            ["01:00:00", 3600],
            ["08:30:00", 30600],
        ];

        foreach($kvs as $kv) {
            list($time, $expectedSeconds) = $kv;

            $this->assertEquals(TimeHelper::timeToSeconds($time), $expectedSeconds);
        }
    }  
}
