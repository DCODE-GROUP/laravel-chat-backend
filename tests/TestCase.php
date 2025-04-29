<?php

namespace Dcodegroup\LaravelChat;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends Orchestra
{
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        //        QueryBuilderRequest::resetDelimiters();
        //
        //        Factory::guessFactoryNamesUsing(
        //            fn (string $modelName) => 'Spatie\\QueryBuilder\\Database\\Factories\\'.class_basename($modelName).'Factory'
        //        );
    }

    protected function setUpDatabase(Application $app)
    {

        $chatTableMigration = require __DIR__.'/../database/migrations/create_chat_tables.php.stub';

        $chatTableMigration->up();
    }
}
