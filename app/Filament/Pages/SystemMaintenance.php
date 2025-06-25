<?php

namespace App\Filament\Pages;

use App\Enums\SystemPermissions;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemMaintenance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $title = 'System Maintenance';

    protected static ?string $navigationLabel = 'System Maintenance';

    protected static ?int $navigationSort = 100;

    public static function canAccess(): bool
    {
        return Auth::user()->hasPermissionTo(SystemPermissions::ManageSettings);
    }

    protected static string $view = 'filament.pages.system-maintenance';

    public function mount(): void
    {
        // Handle action from URL parameters
        $action = request()->get('action');

        if ($action === 'clear_all_cache') {
            $this->clearAllCache();
        } elseif ($action === 'optimize') {
            $this->optimize();
        }
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('clear_all_cache')
                ->label('Clear All Cache')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear All Cache')
                ->modalDescription('This will clear all application cache, config cache, route cache, and view cache. This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, Clear All Cache')
                ->action(fn () => $this->clearAllCache())
                ->after(fn () => $this->dispatch('close-modal')),

            Action::make('optimize')
                ->label('Optimize Application')
                ->icon('heroicon-o-rocket-launch')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Optimize Application')
                ->modalDescription('This will run php artisan optimize to cache configuration, routes, and views for better performance. This may take a few seconds.')
                ->modalSubmitActionLabel('Yes, Optimize Application')
                ->action(fn () => $this->optimize())
                ->after(fn () => $this->dispatch('close-modal')),
        ];
    }

    protected function clearAllCache(): void
    {
        try {
            // Clear all types of cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('clear-compiled');

            Notification::make()
                ->title('Cache Cleared Successfully')
                ->body('All application cache has been cleared.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Cache Clear Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function optimize(): void
    {
        try {
            Artisan::call('optimize');

            Notification::make()
                ->title('Application Optimized')
                ->body('The application has been optimized for better performance.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Optimization Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
