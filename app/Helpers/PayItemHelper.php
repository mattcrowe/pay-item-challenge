<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayItemHelper
{       
    public static function calcAmountToPayInCents($timeWorked, $hourlyRateInCents, $deduction = 30) {
        if (!$hourlyRateInCents) {
            return 0;
        }

        $secondsWorked = TimeHelper::timeToSeconds($timeWorked);
        if (!$secondsWorked) {
            return 0;
        }

        # hoursWorked * hourlyRateInCents * deductionRate
        $amount = ($secondsWorked/60/60) * $hourlyRateInCents * ($deduction/100);        

        return intval(round($amount, 0, PHP_ROUND_HALF_UP));
    }
}
