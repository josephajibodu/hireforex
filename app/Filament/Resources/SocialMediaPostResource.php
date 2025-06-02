<?php

namespace App\Filament\Resources;

use App\Enums\SocialMediaType;
use App\Filament\Resources\SocialMediaPostResource\Pages;
use App\Filament\Resources\SocialMediaPostResource\RelationManagers;
use App\Models\SocialMediaPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialMediaPostResource extends Resource
{
    protected static ?string $model = SocialMediaPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-viewfinder-circle';

    protected static ?string $navigationGroup = 'Others';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->directory('social-media-images')
                    ->image()
                    ->imageEditorAspectRatios([
                        '9:16',
                        '3:4',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->label('Post Content')
                    ->rows(5)
                    ->required(),
                Forms\Components\Select::make('platform')
                    ->native(false)
                    ->options(SocialMediaType::class)
                    ->required()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->square(),
                Tables\Columns\TextColumn::make('content'),
                Tables\Columns\TextColumn::make('platform')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSocialMediaPosts::route('/'),
            'create' => Pages\CreateSocialMediaPost::route('/create'),
            'edit' => Pages\EditSocialMediaPost::route('/{record}/edit'),
        ];
    }
}
