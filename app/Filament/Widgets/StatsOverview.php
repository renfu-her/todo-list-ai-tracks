<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Todo;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('總專案數', Project::count())
                ->description('所有專案')
                ->descriptionIcon('heroicon-m-folder')
                ->color('primary'),

            Stat::make('總待辦事項', Todo::count())
                ->description('所有待辦事項')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('進行中的專案', Project::where('status', 'in_progress')->count())
                ->description('正在進行的專案')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('待處理的待辦事項', Todo::where('status', 'pending')->count())
                ->description('需要處理的待辦事項')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),

            Stat::make('已完成的專案', Project::where('status', 'completed')->count())
                ->description('已完成的專案')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('已完成的待辦事項', Todo::where('status', 'completed')->count())
                ->description('已完成的待辦事項')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('總使用者數', User::count())
                ->description('系統使用者')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
} 