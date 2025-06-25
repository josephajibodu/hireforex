<?php

namespace App\Filament\Resources\GiftCardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Unique code for this gift card unit'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_used')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_used')
                    ->options([
                        '1' => 'Used',
                        '0' => 'Unused',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('generate_units')
                    ->label('Generate Units')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Section::make('Generate Gift Card Units')
                            ->description('Create multiple gift card units with codes')
                            ->schema([
                                Toggle::make('use_random_codes')
                                    ->label('Generate Random Codes')
                                    ->default(true)
                                    ->helperText('Enable to generate random codes, disable to use predefined codes')
                                    ->reactive(),

                                TextInput::make('quantity')
                                    ->label('Number of Units')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(1000)
                                    ->required()
                                    ->visible(fn ($get) => $get('use_random_codes'))
                                    ->helperText('Number of random codes to generate (1-1000)'),

                                TextInput::make('code_length')
                                    ->label('Code Length')
                                    ->numeric()
                                    ->minValue(6)
                                    ->maxValue(20)
                                    ->default(12)
                                    ->required()
                                    ->visible(fn ($get) => $get('use_random_codes'))
                                    ->helperText('Length of each random code (6-20 characters)'),

                                Textarea::make('predefined_codes')
                                    ->label('Predefined Codes')
                                    ->placeholder('CODE1,CODE2,CODE3,CODE4')
                                    ->required()
                                    ->visible(fn ($get) => !$get('use_random_codes'))
                                    ->helperText('Enter codes separated by commas (e.g., ABC123,DEF456,GHI789)')
                                    ->rows(5),
                            ])
                    ])
                    ->action(function (array $data, $record) {
                        $giftCard = $this->getOwnerRecord();
                        $generatedCount = 0;
                        $errors = [];

                        if ($data['use_random_codes']) {
                            // Generate random codes
                            $quantity = (int) $data['quantity'];
                            $codeLength = (int) $data['code_length'];

                            for ($i = 0; $i < $quantity; $i++) {
                                try {
                                    $code = $this->generateUniqueCode($codeLength);
                                    $giftCard->units()->create([
                                        'code' => $code,
                                        'is_used' => false
                                    ]);
                                    $generatedCount++;
                                } catch (\Exception $e) {
                                    $errors[] = "Failed to generate code #" . ($i + 1) . ": " . $e->getMessage();
                                }
                            }
                        } else {
                            // Use predefined codes
                            $codes = array_map('trim', explode(',', $data['predefined_codes']));
                            $codes = array_filter($codes); // Remove empty entries

                            foreach ($codes as $code) {
                                try {
                                    // Check if code already exists
                                    $existingCode = $giftCard->units()->where('code', $code)->first();
                                    if ($existingCode) {
                                        $errors[] = "Code '{$code}' already exists";
                                        continue;
                                    }

                                    $giftCard->units()->create([
                                        'code' => $code,
                                        'is_used' => false
                                    ]);
                                    $generatedCount++;
                                } catch (\Exception $e) {
                                    $errors[] = "Failed to create code '{$code}': " . $e->getMessage();
                                }
                            }
                        }

                        // Show notification
                        if ($generatedCount > 0) {
                            $message = "Successfully generated {$generatedCount} gift card unit(s)";
                            if (!empty($errors)) {
                                $message .= " with " . count($errors) . " error(s)";
                            }

                            Notification::make()
                                ->title('Units Generated')
                                ->body($message)
                                ->success()
                                ->send();
                        }

                        if (!empty($errors)) {
                            Notification::make()
                                ->title('Generation Errors')
                                ->body(implode("\n", $errors))
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalHeading('Generate Gift Card Units')
                    ->modalDescription('Create multiple gift card units with either random or predefined codes')
                    ->modalSubmitActionLabel('Generate Units'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Generate a unique random code for gift card units
     */
    private function generateUniqueCode(int $length): string
    {
        $maxAttempts = 100;
        $attempts = 0;

        do {
            $code = strtoupper(Str::random($length));
            $attempts++;

            // Check if code already exists for this gift card
            $exists = $this->getOwnerRecord()->units()->where('code', $code)->exists();

            if (!$exists) {
                return $code;
            }
        } while ($attempts < $maxAttempts);

        throw new \Exception("Unable to generate unique code after {$maxAttempts} attempts");
    }
}