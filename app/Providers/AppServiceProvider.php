<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

   public function boot()
    {
        View::composer('partials.sidebar', function ($view) {
            $menus = Menu::with('children')
                         ->whereNull('parent_id')
                         ->orderBy('order')
                         ->get();

            $view->with('menus', $menus);
        });
    }

}




