<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('max_size', function ($attribute, $value, $parameters, $validator) {
            $total_size = array_reduce($value, function ($sum, $item) {
                $sum += filesize($item->path());

                return $sum;
            });

            return $total_size < $parameters[0] * 1024;
        });

        Validator::replacer('max_size', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':max_size', $parameters[0], $message);
        });
    }
}
