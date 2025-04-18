<?php


namespace Dcodegroup\LaravelChat;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class LaravelChatServiceProvider extends ServiceProvider
{
        public function boot(): void
        {
            $this->offerPublishing();
            $this->registerRoutes();
            $this->loadTranslationsFrom(__DIR__.'/../lang', 'activity-log-translations');
        }

    protected function setupMigrations()
    {
        if ($this->app->environment('local')) {
            if (! Schema::hasTable('chats')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_chats_tables.stub.php' => $this->app->databasePath('migrations/'.date('Y_m_d_His', time()).'_create_chats_tables.php'),
                ], 'laravel-chat-migrations');
            }

            if (! Schema::hasTable('chat_messages')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_chat_messages_tables.stub.php' => $this->app->databasePath('migrations/'.date('Y_m_d_His', time()).'_create_chat_messages_tables.php'),
                ], 'laravel-chat-migrations');
            }

            if (! Schema::hasTable('chat_users')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_chat_users_tables.stub.php' => $this->app->databasePath('migrations/'.date('Y_m_d_His', time()).'_create_chat_users_tables.php'),
                ], 'laravel-chat-migrations');
            }
        }
    }
}