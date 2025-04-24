<?php

namespace Dcodegroup\LaravelChat;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\TestCase as Orchestra;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        QueryBuilderRequest::resetDelimiters();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\QueryBuilder\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function setUpDatabase(Application $app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('transport', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('client', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        $chatTableMigration = require __DIR__.'/../database/migrations/create_chat_tables.php.stub';

        $chatTableMigration->up();
    }
}
