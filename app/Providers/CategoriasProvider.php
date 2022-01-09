<?php

namespace App\Providers;

use View;
use App\CategoriaReceta;
use Illuminate\Support\ServiceProvider;

class CategoriasProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // cuando no vas a usar cosas que laravel ofrece
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() // cuando requires algo de laravel primero
    {
        // pasar las categorias de las recetas a cualquier view
        View::composer('*', function($view) {
            $categorias = CategoriaReceta::all();
            $view->with('categorias', $categorias);
        });
    }
}
