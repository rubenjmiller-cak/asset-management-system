<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Models\Asset;
use App\Models\AssetSubType;
use App\Models\AssetTertiaryType;
use App\Models\AssetType;
use App\Models\Building;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

            Forms\Components\Section::make('Classification')->schema([
                Forms\Components\Select::make('AssetType')
                    ->label('Asset Type')
                    ->options(fn() => AssetType::orderBy('AssetType')->pluck('AssetType', 'AssetTypeID')),
                Forms\Components\Select::make('AssetSubType')
                    ->label('Subtype')
                    ->options(fn() => AssetSubType::orderBy('AssetSubType')->pluck('AssetSubType', 'AssetSubTypeID')),
                Forms\Components\Select::make('AssetTertiaryType')
                    ->label('Tertiary Type')
                    ->options(fn() => AssetTertiaryType::orderBy('TertiaryType')->pluck('TertiaryType', 'TertiaryTypeID')),
                Forms\Components\Select::make('AssetBuilding')
                    ->label('Building')
                    ->options(fn() => Building::orderBy('Name')->pluck('Name', 'BuildingID')),
            ])->columns(2),

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
                Tables\Columns\TextColumn::make('typeLookup.AssetType')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subTypeLookup.AssetSubType')
                    ->label('Subtype')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('building.Name')
                    ->label('Building')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('SerialNumber')
                    ->label('Serial #')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('DateOfInstall')
                    ->label('Installed')
                    ->date('m/d/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('PurchasePrice')
                    ->label('Cost')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('AssetArchive')
                    ->label('Archived')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type_filter')
                    ->label('Asset Type')
                    ->searchable()
                    ->options(fn() => AssetType::orderBy('AssetType')->pluck('AssetType', 'AssetTypeID'))
                    ->query(fn(Builder $query, array $data) => $query->when(
                        filled($data['value']),
                        fn($q) => $q->where('AssetType', $data['value'])
                    )),

                SelectFilter::make('subtype_filter')
                    ->label('Subtype')
                    ->searchable()
                    ->options(fn() => self::subtypeOptions())
                    ->query(fn(Builder $query, array $data) => $query->when(
                        filled($data['value']),
                        fn($q) => $q->where('AssetSubType', $data['value'])
                    )),

                SelectFilter::make('tertiary_filter')
                    ->label('Tertiary Type')
                    ->searchable()
                    ->options(fn() => AssetTertiaryType::orderBy('TertiaryType')->pluck('TertiaryType', 'TertiaryTypeID'))
                    ->query(fn(Builder $query, array $data) => $query->when(
                        filled($data['value']),
                        fn($q) => $q->where('AssetTertiaryType', $data['value'])
                    )),

                SelectFilter::make('building_filter')
                    ->label('Building')
                    ->searchable()
                    ->options(fn() => Building::orderBy('Name')->pluck('Name', 'BuildingID'))
                    ->query(fn(Builder $query, array $data) => $query->when(
                        filled($data['value']),
                        fn($q) => $q->where('AssetBuilding', $data['value'])
                    )),

                Filter::make('cak_id')
                    ->label('CAK ID')
                    ->form([
                        Forms\Components\TextInput::make('cak_id')
                            ->label('CAK ID')
                            ->placeholder('Search CAK ID…'),
                    ])
                    ->query(fn(Builder $query, array $data) => $query->when(
                        filled($data['cak_id']),
                        fn($q) => $q->where('CAKID', 'like', '%' . $data['cak_id'] . '%')
                    ))
                    ->indicateUsing(fn(array $data) => filled($data['cak_id']) ? 'CAK ID: ' . $data['cak_id'] : null),
            ])
            // Always-visible horizontal strip — mirrors PHPMaker's extended search bar
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->filtersTriggerAction(fn($action) => $action->hidden())
            ->filtersFormColumns(5)
            ->defaultSort('AssetID', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    protected static function subtypeOptions(): array
    {
        return AssetSubType::query()
            ->join('tblAssets_LU_Type', 'tblAssets_LU_SubType.AssetTypeID', '=', 'tblAssets_LU_Type.AssetTypeID')
            ->orderBy('tblAssets_LU_Type.AssetType')
            ->orderBy('tblAssets_LU_SubType.AssetSubType')
            ->selectRaw('tblAssets_LU_SubType.AssetSubTypeID, CONCAT(tblAssets_LU_Type.AssetType, " > ", tblAssets_LU_SubType.AssetSubType) as label')
            ->pluck('label', 'AssetSubTypeID')
            ->toArray();
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
