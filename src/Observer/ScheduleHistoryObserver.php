<?php

namespace HusamTariq\FilamentDatabaseSchedule\Observer;

use HusamTariq\FilamentDatabaseSchedule\Models\ScheduleHistory;

class ScheduleHistoryObserver
{
    /**
     * Handle the ScheduleHistory "created" event.
     */
    public function created(ScheduleHistory $scheduleHistory): void
    {
        $maximum_history_count = config('filament-database-schedule.history_max_count');
        if (isset($maximum_history_count)) {
            $schedule = $scheduleHistory->command;
            $history_count = $schedule->histories()->count();
            if ($history_count > $maximum_history_count) {
                $keepIds = $schedule->histories()->latest()->take($maximum_history_count)->pluck('id');
                $schedule->histories()->whereNotIn('id', $keepIds)->delete();
            }
        }
    }

}