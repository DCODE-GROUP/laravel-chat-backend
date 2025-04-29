<?php

namespace Dcodegroup\LaravelChat;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends Orchestra
{
    use DatabaseTruncation;
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        //        QueryBuilderRequest::resetDelimiters();
        //
        //        Factory::guessFactoryNamesUsing(
        //            fn (string $modelName) => 'Spatie\\QueryBuilder\\Database\\Factories\\'.class_basename($modelName).'Factory'
        //        );

        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        $chatTableMigration = require __DIR__.'/../database/migrations/create_chat_tables.php.stub';

        $chatTableMigration->up();
    }

    protected function defineDatabaseMigrations()
    {
        artisan($this, 'migrate', ['--database' => 'forge']);
        $chatTableMigration = require __DIR__.'/../database/migrations/create_chat_tables.php.stub';

        $chatTableMigration->up();

        $this->beforeApplicationDestroyed(
            fn () => artisan($this, 'migrate:rollback', ['--database' => 'forge'])
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelChatServiceProvider::class,
        ];
    }
}
