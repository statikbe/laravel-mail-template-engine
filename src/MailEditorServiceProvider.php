<?php

namespace Statikbe\LaravelMailEditor;

use Illuminate\Support\ServiceProvider;

class MailEditorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mail-template-engine.php', 'mail-template-engine'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mail-template-engine.php' => config_path('mail-template-engine.php'),
        ], ['config', 'mail-template-engine']);

        $this->publishes([
            __DIR__.'/../database/migrations/create_mail_templates_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_mail_templates_table.php'),
        ], ['migrations', 'mail-template-engine']);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/statikbe'),
        ], 'views');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'statikbe');
    }
}
