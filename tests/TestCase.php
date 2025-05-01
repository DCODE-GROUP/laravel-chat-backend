<?php

namespace Dcodegroup\LaravelChat\Tests;

use Dcodegroup\LaravelChat\LaravelChatServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;

use function Orchestra\Testbench\artisan;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends Orchestra
{
    use RefreshDatabase;
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        //        QueryBuilderRequest::resetDelimiters();
        //
        //        Factory::guessFactoryNamesUsing(
        //            fn (string $modelName) => 'Spatie\\QueryBuilder\\Database\\Factories\\'.class_basename($modelName).'Factory'
        //        );

        //        $this->setUpDatabase();
    }

    //    protected function setUpDatabase()
    //    {
    //        $chatTableMigration = require __DIR__.'/../database/migrations/create_chat_tables.php.stub';
    //
    //        $chatTableMigration->up();
    //    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        tap($app['config'], function (Repository $config) {
            $config->set('database.default', 'testbench');
            $config->set('database.connections.testbench', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);

            // Setup queue database connections.
            //            $config->set([
            //                'queue.batching.database' => 'testbench',
            //                'queue.failed.database' => 'testbench',
            //            ]);
        });
    }

    protected function defineDatabaseMigrations()
    {
        artisan($this, 'migrate', ['--database' => 'testing']);
        $chatTableMigration = require __DIR__.'/../database/migrations/create_chats_tables.stub.php';

        $chatTableMigration->up();

        $this->beforeApplicationDestroyed(
            fn () => artisan($this, 'migrate:rollback', ['--database' => 'testing'])
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelChatServiceProvider::class,
        ];
    }
}
