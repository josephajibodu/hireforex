<x-filament-panels::page>
    <div class="space-y-6">
        <!-- System Information -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                System Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Laravel Version</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ app()->version() }}</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">PHP Version</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ phpversion() }}</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Environment</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ config('app.env') }}</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Debug Mode</div>
                    <div class="text-lg font-semibold {{ config('app.debug') ? 'text-red-600' : 'text-green-600' }}">
                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Cache Driver</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ config('cache.default') }}</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Queue Driver</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ config('queue.default') }}</div>
                </div>
            </div>
        </div>

        <!-- Maintenance Actions -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Maintenance Actions
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cache Management -->
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Clear All Cache</h3>
                        </div>
                    </div>
                    <p class="text-sm text-red-700 dark:text-red-300 mb-3">
                        Clear all application cache, config cache, route cache, and view cache. Use this when you need to refresh all cached data.
                    </p>
                    <button
                        type="button"
                        onclick="confirmClearCache()"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        Clear All Cache
                    </button>
                </div>

                <!-- Optimization -->
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Optimize Application</h3>
                        </div>
                    </div>
                    <p class="text-sm text-green-700 dark:text-green-300 mb-3">
                        Run php artisan optimize to cache configuration, routes, and views for better performance. Use this in production.
                    </p>
                    <button
                        type="button"
                        onclick="confirmOptimize()"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        Optimize Application
                    </button>
                </div>
            </div>
        </div>

        <!-- Cache Status -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Cache Status
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Application Cache</div>
                    <div class="text-lg font-semibold text-blue-900 dark:text-blue-100">
                        {{ Cache::has('laravel_cache') ? 'Cached' : 'Not Cached' }}
                    </div>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                    <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Config Cache</div>
                    <div class="text-lg font-semibold text-purple-900 dark:text-purple-100">
                        {{ file_exists(storage_path('bootstrap/cache/config.php')) ? 'Cached' : 'Not Cached' }}
                    </div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                    <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Route Cache</div>
                    <div class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">
                        {{ file_exists(storage_path('bootstrap/cache/routes.php')) ? 'Cached' : 'Not Cached' }}
                    </div>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                    <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">View Cache</div>
                    <div class="text-lg font-semibold text-indigo-900 dark:text-indigo-100">
                        {{ Storage::disk('local')->exists('framework/views') ? 'Cached' : 'Not Cached' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmClearCache() {
            if (confirm('Are you sure you want to clear all cache? This action cannot be undone.')) {
                // This will trigger the header action
                window.location.href = '{{ route("filament.admin.pages.system-maintenance") }}?action=clear_all_cache';
            }
        }

        function confirmOptimize() {
            if (confirm('Are you sure you want to optimize the application? This will cache configuration, routes, and views.')) {
                // This will trigger the header action
                window.location.href = '{{ route("filament.admin.pages.system-maintenance") }}?action=optimize';
            }
        }
    </script>
</x-filament-panels::page>
