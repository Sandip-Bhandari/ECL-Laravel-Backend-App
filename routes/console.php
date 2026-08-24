<?php

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    app(\App\Http\Controllers\Api\NotificationController::class)->sendDeadlineAlerts();
})->hourly();
