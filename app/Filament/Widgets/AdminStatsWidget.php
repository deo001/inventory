<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AdminStatsWidget extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('Total Workers', User::count()),
            Stat::make('Total Products', Product::count()),
            Stat::make('Products sold today', Sale::whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])->sum('quantity')),
        ];
    }
}
