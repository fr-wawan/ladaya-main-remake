<?php

namespace App\Filament\Resources\HalamanResource\Widgets;

use App\Models\Halaman;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HalamanOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Halaman',Halaman::all()->count())
        ];
    }
}
