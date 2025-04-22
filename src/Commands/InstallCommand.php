<?php

namespace Dcodegroup\LaravelChat\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-chat:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Laravel Chat Feature';

    public function handle(): void
    {
        if (app()->environment('local')) {
            $this->comment('Publishing Laravel Chat Migrations');
            $this->callSilent('vendor:publish', ['--tag' => 'laravel-chat-migrations']);
        }

        $this->comment('Publishing Laravel ChatConfiguration...');
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-chat-config']);

        $this->comment('Publishing Laravel Chat Translations...');
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-chat-translations']);

        $this->info('Laravel Chat scaffolding installed successfully.');
    }
}
