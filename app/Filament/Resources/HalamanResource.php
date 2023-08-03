<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Halaman;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HalamanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\HalamanResource\RelationManagers;
use App\Filament\Resources\HalamanResource\Widgets\HalamanOverview;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Set;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class HalamanResource extends Resource
{
    protected static ?string $model = Halaman::class;


    protected static ?string $navigationLabel = 'Halaman';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'System Management';

    public static function getPluralModelLabel(): string
    {
        return static::$navigationLabel;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')->reactive()->afterStateUpdated(fn (Set $set,?string $state) => $set('slug',Str::slug($state)))->required(),
                TextInput::make('slug')->maxLength(255),
                RichEditor::make('konten')->columnSpanFull(),
                FileUpload::make('gambar')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul'),
                TextColumn::make('slug'),
                ImageColumn::make('gambar')->square(),
                TextColumn::make('updated_at')->dateTime()
            ])
            ->filters([
                //
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
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getWidgets(): array {
        return [
            HalamanResource\Widgets\HalamanOverview::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHalamen::route('/'),
        ];
    }    
}
