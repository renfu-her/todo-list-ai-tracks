<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class TodosRelationManager extends RelationManager
{
    protected static string $relationship = 'todos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('待辦事項資訊')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('標題')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TinyEditor::make('description')
                            ->label('描述')
                            ->columnSpanFull(),

                        Flatpickr::make('due_date')
                            ->label('截止日期')
                            ->dateFormat('Y-m-d H:i')
                            ->allowInput()
                            ->altInput(true)
                            ->altFormat('Y-m-d H:i')
                            ->enableTime()
                            ->customConfig([
                                'locale' => 'zh_tw',
                            ]),

                        Forms\Components\Select::make('priority')
                            ->label('優先級')
                            ->options([
                                'low' => '低',
                                'medium' => '中',
                                'high' => '高',
                            ])
                            ->default('medium')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('狀態')
                            ->options([
                                'pending' => '待處理',
                                'in_progress' => '進行中',
                                'completed' => '已完成',
                                'cancelled' => '已取消',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->label('負責人')
                            ->relationship('user', 'name')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('標題')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('負責人')
                    ->sortable(),

                Tables\Columns\SelectColumn::make('priority')
                    ->label('優先級')
                    ->options([
                        'low' => '低',
                        'medium' => '中',
                        'high' => '高',
                    ])
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('狀態')
                    ->options([
                        'pending' => '待處理',
                        'in_progress' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('截止日期')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('priority')
                    ->label('優先級')
                    ->options([
                        'low' => '低',
                        'medium' => '中',
                        'high' => '高',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('狀態')
                    ->options([
                        'pending' => '待處理',
                        'in_progress' => '進行中',
                        'completed' => '已完成',
                        'cancelled' => '已取消',
                    ]),

                Tables\Filters\SelectFilter::make('user')
                    ->label('負責人')
                    ->relationship('user', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
