<?php

namespace HusamTariq\FilamentDatabaseSchedule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ScheduleHistory extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    protected $fillable = [
        'command',
        'params',
        'output',
        'options',
    ];

    protected $casts = [
        'params' => 'array',
        'options' => 'array',
    ];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = Config::get('filament-database-schedule.table.schedule_histories', 'schedule_histories');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $max_history_count = Config::get('filament-database-schedule.max_schedule_history_count_for_schedule', null);
            if (isset($max_history_count) && $max_history_count >= 0) {
                // Keep only the last $config_last_total records
                $array = $model::where('schedule_id', $model->schedule_id)->latest()->skip($max_history_count)->get();
                foreach ($array as $record) { $record->delete();}
            }
        });
    }

    public function command()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }
}
