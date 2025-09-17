<?php

namespace Dcodegroup\DCodeChat\Tests;

use Dcodegroup\DCodeChat\DCodeChatServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;
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

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $refClass = new \ReflectionClass($modelName);
            $namespace = $refClass->getNamespaceName();
            if ($namespace === 'Dcodegroup\DCodeChat\Models') {
                return 'Dcodegroup\\DCodeChat\\Factories\\'.class_basename($modelName).'Factory';
            }

            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });

        // Publish migrations
        $this->artisan('vendor:publish', [
            '--tag' => 'dcode-chat-migrations',
            '--force' => true,
        ])->run();
    }

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
        });
    }

    protected function defineDatabaseMigrations()
    {
        artisan($this, 'migrate', ['--database' => 'testing']);

        $this->beforeApplicationDestroyed(
            fn () => artisan($this, 'migrate:rollback', ['--database' => 'testing'])
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            DCodeChatServiceProvider::class,
        ];
    }
}
