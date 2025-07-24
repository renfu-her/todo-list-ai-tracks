<?php

namespace App\Filament\Widgets;

use App\Models\Todo;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTodos extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Todo::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('標題')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('專案')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('負責人')
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->label('優先級')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'high' => '高',
                        'medium' => '中',
                        'low' => '低',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('狀態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'danger',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => '待處理',
                        'in_progress' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    }),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('截止日期')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('查看')
                    ->url(fn (Todo $record): string => route('filament.admin.resources.todos.edit', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }
} 