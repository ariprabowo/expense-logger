<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        /* Get Income */
        $income = Transaction::incomes()->whereBetween('date_transaction', [$startDate, $endDate])->sum('amount');

        /* Get Expense */
        $expense = Transaction::expenses()->whereBetween('date_transaction', [$startDate, $endDate])->sum('amount');

        /* Get Difference */
        $diff = $income - $expense;

        return [
            Stat::make('Total Income', number_format($income)),
            Stat::make('Total Expense', number_format($expense)),
            Stat::make('Total Diff', number_format($diff)),
        ];
    }
}
