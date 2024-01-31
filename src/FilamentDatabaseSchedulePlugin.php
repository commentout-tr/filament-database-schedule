<?php

namespace HusamTariq\FilamentDatabaseSchedule;

use Filament\Contracts\Plugin;
use Filament\Panel;
use HusamTariq\FilamentDatabaseSchedule\Filament\Resources\ScheduleResource;

class FilamentDatabaseSchedulePlugin implements Plugin
{
    public static string $name = 'filament-database-schedule';

    public static function make(): static
    {
        $static = app(static::class);
        return $static;
    }


    public function getId(): string
    {
        return static::$name;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                ScheduleResource::class,
            ])
          ;
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
