<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Bookmark;

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
        Blade::directive('permission', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
        View::composer('frontend.partials.header', function ($view) {
        $count = 0;
        if (auth()->check()) {
            $count = Bookmark::where('user_id', auth()->id())->count();
        }
        $view->with('bookmarkCount', $count);
    });
    }
}
