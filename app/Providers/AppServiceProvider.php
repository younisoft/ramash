<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
class AppServiceProvider extends ServiceProvider {
    public function boot(): void {
        View::composer('layouts.app', function ($view) {
            $view->with('navCategories', Category::visible()->orderBy('sort')->get());
            $view->with('cartCount', array_sum(array_column(app(\App\Services\Cart::class)->summary()['lines'], 'quantity')));
        });
    }
}
