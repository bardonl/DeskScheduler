<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ScheduleController;

class CalendarController extends Controller
{
    public function get(ScheduleController $scheduleController)
    {
        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];

        $scheduled = $scheduleController->getScheduled();

        return view('dashboard',['days' => $days, 'scheduled' => $scheduled]);
    }
}
