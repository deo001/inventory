<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Chart';

    protected static ?string $description = 'Chart showing products sold in current week';

    protected function getData(): array
    {
        $data = Trend::model(Sale::class)
            ->between(
                start: now()->startOfWeek(),
                end: now()->endOfWeek(),
            )
            ->perDay()
            ->sum('quantity');

        return [
            'datasets' => [
                [
                    'label' => 'Day\'s products',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ]
            ],
            'labels' => $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->dayName)

        ];
         return [];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
