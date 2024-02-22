<?php

namespace HusamTariq\FilamentDatabaseSchedule\Observer;

use HusamTariq\FilamentDatabaseSchedule\Models\Schedule;
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
            $schedule = Schedule::find($scheduleHistory->schedule_id);
            $history_count = $schedule->histories()->count();
            if ($history_count > $maximum_history_count) {
                $willDeletedIds = $schedule->histories()
                         ->orderBy('created_at','desc')
                         ->skip($maximum_history_count)
                         ->pluck('id');
                ScheduleHistory::whereIn('id', $willDeletedIds)->delete();
            }
        }
    }

}