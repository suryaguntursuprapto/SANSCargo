<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('wibDateTime', function ($expression) {
            return "<?php echo ($expression)->setTimezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'; ?>";
        });
         // Pastikan semua view memiliki akses ke informasi locale terkini
        View::composer('*', function ($view) {
            $locale = Session::get('locale', config('app.locale', 'id'));
            
            // Paksa app locale untuk selalu sesuai dengan session locale
            App::setLocale($locale);
            
            // Tambahkan variabel ke semua view
            $view->with('currentLocale', $locale);
        });
        }
}
