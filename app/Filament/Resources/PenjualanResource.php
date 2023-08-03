<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Tiket;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Penjualan;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PenjualanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'System Management';
    protected static ?string $navigationLabel = 'Penjualan';
    

    public static function getPluralModelLabel(): string
    {
        return static::$navigationLabel;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor')->label('No. Invoice')->maxLength(255),
                TextInput::make('nama_pelanggan')->maxLength(255),
                TextInput::make('hp_pelanggan')->maxLength(255)->numeric(),
                TextInput::make('email_pelanggan')->maxLength(255)->email(),
                Select::make('tiket_id')->relationship('tiket','nama',)->reactive()->afterStateUpdated(function(Set $set,Get $get,?string $state){
                    $tiket = Tiket::find($state);

                    if($tiket){
                        $set('harga',(string) $tiket->harga);

                        $total = doubleval($get('harga')) * intval($get('kuantiti'));
                        $set('total',(string) $total);
                    }
                }),
                TextInput::make('harga')->mask(RawJs::make(<<<'JS'
                $money($input)
                JS))->numeric()->reactive(),

                TextInput::make('kuantiti')->numeric()->reactive()->afterStateUpdated(function(Set $set,Get $get,?string $state){
                    $total = doubleval($get('harga')) * intval($get('kuantiti'));
                    $set('total',(string) $total);
                }),

                Select::make('bank_id')->label('Bank')->relationship('bank','nama'),

                TextInput::make('total')->numeric()->mask(RawJs::make(<<<'JS'
                $money($input)
                JS))->extraInputAttributes([
                    'readonly' => true
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('nomor'),
            TextColumn::make('nama_pelanggan'),
            TextColumn::make('hp_pelanggan'),
            TextColumn::make('email_pelanggan'),
            TextColumn::make('tiket.nama'),
            TextColumn::make('harga')->money('idr'),
            TextColumn::make('kuantiti'),
            TextColumn::make('bank.nama'),
            TextColumn::make('total')->money('idr'),
            TextColumn::make('created_at')->dateTime()
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
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePenjualans::route('/'),
        ];
    }    
}
