<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = '任務管理';
    protected static ?string $navigationLabel = '專案管理';
    protected static ?string $modelLabel = '專案';
    protected static ?string $pluralModelLabel = '專案';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('專案資訊')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('專案名稱')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TinyEditor::make('description')
                            ->label('專案描述')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('專案狀態')
                            ->options([
                                'planning' => '規劃中',
                                'in_progress' => '進行中',
                                'completed' => '已完成',
                                'on_hold' => '暫停',
                                'cancelled' => '已取消',
                            ])
                            ->default('planning')
                            ->required(),

                        Flatpickr::make('start_date')
                            ->label('開始日期')
                            ->dateFormat('Y-m-d')
                            ->allowInput()
                            ->altInput(true)
                            ->altFormat('Y-m-d')
                            ->customConfig([
                                'locale' => 'zh_tw',
                            ]),

                        Flatpickr::make('end_date')
                            ->label('結束日期')
                            ->dateFormat('Y-m-d')
                            ->allowInput()
                            ->altInput(true)
                            ->altFormat('Y-m-d')
                            ->customConfig([
                                'locale' => 'zh_tw',
                            ]),

                        Forms\Components\Select::make('user_id')
                            ->label('專案負責人')
                            ->relationship('user', 'name')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('專案名稱')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('負責人')
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('狀態')
                    ->options([
                        'planning' => '規劃中',
                        'in_progress' => '進行中',
                        'completed' => '已完成',
                        'on_hold' => '暫停',
                        'cancelled' => '已取消',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('開始日期')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('結束日期')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('todos_count')
                    ->label('待辦事項數量')
                    ->counts('todos')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('專案狀態')
                    ->options([
                        'planning' => '規劃中',
                        'in_progress' => '進行中',
                        'completed' => '已完成',
                        'on_hold' => '暫停',
                        'cancelled' => '已取消',
                    ]),

                Tables\Filters\SelectFilter::make('user')
                    ->label('負責人')
                    ->relationship('user', 'name'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\TodosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
