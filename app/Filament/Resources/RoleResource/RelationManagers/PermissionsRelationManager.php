<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultPaginationPageOption(50)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->formatStateUsing(fn($state) => ucfirst($state)),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Add Permission')
                    ->icon('heroicon-o-plus')
                    ->modalHeading("Add Permission to Role")
                    ->modalSubmitActionLabel('Add Permission')
                    ->attachAnother(false)
                    ->after(function () {
                        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Remove')
                    ->after(function () {
                        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
