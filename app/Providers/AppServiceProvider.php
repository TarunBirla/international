<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

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
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('categories')) {
                    $globalCategories = Category::whereNull('parent_id')
                        ->with('subcategories')
                        ->orderBy('name')
                        ->get();
                    $view->with('globalCategories', $globalCategories);
                } else {
                    $view->with('globalCategories', collect());
                }
            } catch (\Throwable $e) {
                $view->with('globalCategories', collect());
            }
        });
    }
}
