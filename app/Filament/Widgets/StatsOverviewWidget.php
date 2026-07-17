<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\Payment;
use App\Models\Property;
use App\Models\UserSubscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Payment::where('payment_status', 'completed')->sum('total_amount');
        $activeSubs = UserSubscription::where('status', 'active')->count();
        $totalProperties = Property::count();
        $totalAgents = Agent::count();

        return [
            Stat::make('Total Revenue', 'INR ' . number_format($totalRevenue, 2))
                ->description('Sum of completed transactions')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Active Subscriptions', $activeSubs)
                ->description('Total active agent subscriptions')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Total Properties', $totalProperties)
                ->description('All properties listed on the portal')
                ->descriptionIcon('heroicon-m-home')
                ->color('warning'),

            Stat::make('Registered Agents', $totalAgents)
                ->description('Active real estate agents')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),
        ];
    }
}
