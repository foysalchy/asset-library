<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Bookmark;
use App\Models\Notification;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('helpers.php');

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Blade::directive('permission', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
        View::composer('frontend.partials.header', function ($view) {
            $bookmarkCount = 0;
            $notifications = collect();
            $unreadCount   = 0;

            if (auth()->check()) {
                $userId = auth()->id();
                $bookmarkCount = Bookmark::where('user_id', $userId)->count();
                $recentNotifications = Notification::latest()->take(50)->get();

                $notifications = $recentNotifications
                    ->reject(fn($n) => $n->isReadBy($userId))
                    ->take(10)
                    ->values();

                $unreadCount = Notification::get()->filter(fn($n) => !$n->isReadBy($userId))->count();;
            }

            $view->with(compact('bookmarkCount', 'notifications', 'unreadCount'));
        });
    }
}
