<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        // Appel et reference à l'observeur
        User::observe(UserObserver::class);

        // Vue côté client
        View::composer('layouts.client', function ($view) {
            $user = Auth::user();

            $view->with([
                'user' => $user,
            ]);
        });

        // Vue côté proprietaire
        View::composer('layouts.proprio', function ($view) {
            $user = Auth::user();

            $view->with([
                'user' => $user,
            ]);
        });

        // Vue côté admin
        View::composer('layouts.admin', function ($view) {

            $proprietaires = User::where('role', 'proprietaire')
                ->select('id', 'name', 'surname', 'email')
                ->orderBy('name')
                ->get();

            $categories = Category::all();

            $user = Auth::user();

            $view->with([
                'proprietaires' => $proprietaires,
                'categories'    => $categories,
                'user'          => $user, // 1 seule variable propre
            ]);
        });
    }
}
