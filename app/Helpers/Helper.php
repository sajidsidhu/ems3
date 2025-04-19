<?php
namespace App\Helpers;

use Carbon\Carbon;

class Helper
{
    public static function calculateWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);

        return $start->diffInDaysFiltered(function (Carbon $date) {
            return ! $date->isWeekend();
        }, $end) + 1; // Add 1 to include start date
    }
}
