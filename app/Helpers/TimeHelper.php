<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeHelper
{
    public static function timeToSeconds($time) {
        if (!$time) {
            return 0;
        }

        return strtotime($time) - strtotime('00:00:00');
    }
}
