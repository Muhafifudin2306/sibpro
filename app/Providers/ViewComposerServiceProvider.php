<?php

namespace App\Providers;



use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Models\Notification;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Melempar data notifikasi ke view layouts.app
        View::composer('layouts.admin.app', function ($view) {
            // Ambil data notifikasi dari database dengan status 0 (unread)
            $notifications = Notification::where('notification_status', 0)->get();

            // Melempar data notifikasi ke view
            $view->with('notifications', $notifications);
        });
    }
}
