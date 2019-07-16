<?php

namespace Demos\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //require __DIR__ . '/../vendor/autoload.php';
        

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'admin');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'admin');
        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'admin');

        $this->publishes([
           __DIR__ . '/views' => base_path('resources/views/vendor/demos/admin')
        ], 'views');

        $this->publishes([
            __DIR__ . '/config' => config_path('demos/admin'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/assets' => public_path('demos/admin', 'public'),
        ], 'assets');

        $this->publishes([
            __DIR__ . '/database/migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        $this->publishes([
            __DIR__.'/lang' => resource_path('lang/demos/admin'),
        ], 'lang');

        if ($this->app->environment() == 'local') {            
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }

        $this->app->register('Demos\Admin\AdminLibServiceProvider');   

        $this->registerHelpers();    



        /**
         * Lang settings singleton
         *
         * @param  \Illuminate\Foundation\Application $app
         * @return object
         */
        \App::singleton('langSettings', function($app) {
            if (\Schema::hasTable('languages')){
                $langs = LangModel::orderBy('order', 'asc')->get();
                $settings = new \stdClass;
                $settings->allLangs = $langs;

                $settings->langs = $langs->filter(function($value){
                    return $value->hidden == 0;
                });

                $settings->hiddenLangs = $langs->filter(function($value){
                    return $value->hidden == 1;
                });

                $settings->defaultLang = $langs->filter(function($value){
                    return $value->default == 1;
                })->first()->code;

                $settings->defaultAdminLang = $langs->filter(function($value){
                    return $value->default_admin == 1;
                })->first()->code;

                $settings->fallbackLang = \Config::get('app.fallback_locale');
                
                return $settings;
            }
        });

        if (\Schema::hasTable('languages')){
            \View::share('langs_list', $langs = app('langSettings')->langs->pluck('code'));  
        }        

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/main.php', 'admin');
    }

    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        // Load the helpers in app/Http/helpers.php
        if (file_exists($file = app_path('Http/helpers.php')))
        {
            require $file;
        }
    }   
}
