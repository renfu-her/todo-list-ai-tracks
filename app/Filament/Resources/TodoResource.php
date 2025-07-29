<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TodoResource\Pages;
use App\Filament\Resources\TodoResource\RelationManagers;
use App\Models\Todo;
use App\Models\User;
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

class TodoResource extends Resource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = '任務管理';
    protected static ?string $navigationLabel = '待辦事項';
    protected static ?string $modelLabel = '待辦事項';
    protected static ?string $pluralModelLabel = '待辦事項';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本資訊')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('標題')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\MarkdownEditor::make('description')
                            ->label('描述')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('project_id')
                            ->label('所屬專案')
                            ->options(Project::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload(),

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
                            ->label('執行者')
                            ->multiple()
                            ->options(User::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder('選擇執行者...'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('標題')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('專案')
                    ->sortable()
                    ->searchable(),



                Tables\Columns\TextColumn::make('user_id')
                    ->label('執行者')
                    ->getStateUsing(function ($record) {
                        if (!$record->user_id) return '無';
                        
                        $userIds = $record->user_id;
                        if (is_string($userIds)) {
                            $userIds = json_decode($userIds, true);
                        }
                        
                        if (!is_array($userIds) || empty($userIds)) return '無';
                        
                        $users = User::whereIn('id', $userIds)->pluck('name')->toArray();
                        return implode(', ', $users);
                    })
                    ->badge()
                    ->color('info'),

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
                Tables\Filters\SelectFilter::make('project')
                    ->label('專案')
                    ->options(\App\Models\Project::pluck('name', 'id')->toArray()),

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
                    ->label('執行者')
                    ->options(User::pluck('name', 'id')->toArray())
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['values'])) {
                            $query->whereJsonContains('user_id', $data['values']);
                        }
                        return $query;
                    }),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTodos::route('/'),
            'create' => Pages\CreateTodo::route('/create'),
            'edit' => Pages\EditTodo::route('/{record}/edit'),
        ];
    }
}
