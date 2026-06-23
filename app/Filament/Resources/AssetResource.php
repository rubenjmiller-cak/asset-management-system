<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Assets';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identification')->schema([
                Forms\Components\TextInput::make('AssetName')->maxLength(250),
                Forms\Components\TextInput::make('CAKID')->label('CAK ID')->maxLength(80),
                Forms\Components\TextInput::make('SerialNumber')->maxLength(80),
                Forms\Components\TextInput::make('Description')->maxLength(255)->columnSpanFull(),
            ])->columns(3),

            Forms\Components\Section::make('Dates & Cost')->schema([
                Forms\Components\DatePicker::make('DateOfPurchase')->label('Purchase Date'),
                Forms\Components\DatePicker::make('DateOfInstall')->label('Install Date'),
                Forms\Components\TextInput::make('PurchasePrice')->numeric()->prefix('$'),
            ])->columns(3),

            Forms\Components\Section::make('Status')->schema([
                Forms\Components\Toggle::make('AssetArchive')->label('Archived'),
                Forms\Components\Textarea::make('Notes')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('AssetID')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetName')
                    ->label('Asset Name')
                    ->sortable()
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('CAKID')
                    ->label('CAK ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('Description')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('SerialNumber')
                    ->label('Serial #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DateOfInstall')
                    ->label('Installed')
                    ->date('m/d/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('PurchasePrice')
                    ->label('Cost')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('AssetArchive')
                    ->label('Archived')
                    ->boolean(),
                Tables\Columns\TextColumn::make('DateAdded')
                    ->label('Added')
                    ->dateTime('m/d/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('AssetArchive')
                    ->label('Archived')
                    ->trueLabel('Archived only')
                    ->falseLabel('Active only')
                    ->placeholder('All assets'),
            ])
            ->defaultSort('AssetID', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'view'  => Pages\ViewAsset::route('/{record}'),
        ];
    }
}
