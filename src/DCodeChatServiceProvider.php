<?php

namespace Dcodegroup\DCodeChat;

use Dcodegroup\DCodeChat\Commands\InstallCommand;
use Dcodegroup\DCodeChat\Commands\SendUnreadNotifications;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DCodeChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->offerPublishing();
        $this->registerCommands();
        $this->setupMigrations();
        $this->registerRoutes();

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'dcode-chat-translations');

        Event::listen(Login::class, function ($event) {
            $event->user->forceFill([
                'last_login_at' => now(),
            ])->save();
        });

        View::composer('*', function ($view) {
            $view->with('ziggy', app('router')->getRoutes());
        });

        // Register the views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dcode-chat');
    }

    protected function offerPublishing(): void
    {
        $this->setupMigrations();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([__DIR__.'/../config/dcode-chat.php' => config_path('dcode-chat.php')], 'dcode-chat-config');
        $this->publishes([__DIR__.'/../lang' => $this->app->langPath()], 'dcode-chat-translations');
    }

    protected function setupMigrations(): void
    {
        if (! $this->migrationsArePublished()) {
            // Chat migrations
            collect([
                'create_chats_table.stub.php',
                'create_chat_messages_table.stub.php',
                'create_chat_users_table.stub.php',
                'create_chat_email_notifications_table.stub.php',
                'add_last_login_to_users.stub.php',
            ])->each(function ($migration, $index) {
                $newPath = database_path('migrations/'.date('Y_m_d_His').'_'.$index.'_'.str_replace('.stub', '', $migration));
                $this->publishes([
                    __DIR__.'/../database/migrations/'.$migration => $newPath,
                ], 'dcode-chat-migrations');
            });
        }
    }

    protected function migrationsArePublished(): bool
    {
        $expectedSuffixes = [
            'create_chats_table.php',
            'create_chat_messages_table.php',
            'create_chat_users_table.php',
            'create_chat_email_notifications_table.php',
        ];

        $migrationFiles = glob(database_path('migrations/*.php'));

        foreach ($migrationFiles as $file) {
            foreach ($expectedSuffixes as $suffix) {
                if (str_ends_with($file, $suffix)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                SendUnreadNotifications::class,
            ]);
        }
    }

    protected function registerRoutes()
    {
        Route::group([
            'middleware' => config('dcode-chat.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/dcode-chat.php');
        });

        Broadcast::routes();
        Broadcast::channel('dcode-chat.{userId}', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/dcode-chat.php', 'dcode-chat');
    }
}
