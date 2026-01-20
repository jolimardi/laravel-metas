<?php

namespace App\Filament\Resources\Metas;

use App\Filament\Resources\Metas\Pages\ManageMetas;
use App\Models\Meta;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MetaResource extends Resource {
    protected static ?string $model = Meta::class;

    // Config du menu et des noms
    protected static ?string $modelLabel = 'meta';
    protected static ?string $pluralModelLabel = 'métas';
    protected static ?string $navigationLabel = 'SEO - Titles & Métas';
    protected static ?int $navigationSort = 900; // Place dans le menu
    protected static string|null|\UnitEnum $navigationGroup = 'Configuration'; // Groupe dans le menu
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare; // Icone dans le menu

    // Attribut utilisé pour le titre d'un item
    protected static ?string $recordTitleAttribute = 'routename';


    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('routename')->required(),
                TextInput::make('uri')->default(null),
                TextInput::make('title')
                    ->label('Title')
                    ->default(null)
                    ->maxLength(60)
                    ->live()
                    ->hint(fn ($state) => (strlen($state ?? '')) . '/60'),
                Textarea::make('description')
                    ->maxLength(160)
                    ->default(null)
                    ->columnSpanFull()
                    ->live()
                    ->hint(fn ($state) => (strlen($state ?? '')) . '/140'),
                Textarea::make('help')->default(null)->columnSpanFull(),
                Toggle::make('locked')->label('Verrouillé')->required(),
            ]);
    }


    public static function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('routename')
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('routename')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('uri')
                    ->sortable()
                    ->searchable(),
                ToggleColumn::make('locked')
                    ->label('Verrouillé'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
//                DeleteAction::make(),
            ])
            ->toolbarActions([
                /*BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),*/
            ]);
    }


    public static function getPages(): array {
        return [
            'index' => ManageMetas::route('/'),
        ];
    }
}
