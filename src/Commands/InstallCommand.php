<?php

namespace Dcodegroup\DCodeChat\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dcode-chat:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the DCode Chat Feature';

    public function handle(): void
    {
        if (app()->environment('local')) {
            $this->comment('Publishing DCode Chat Migrations');
            $this->callSilent('vendor:publish', ['--tag' => 'dcode-chat-migrations']);
        }

        $this->comment('Publishing DCode ChatConfiguration...');
        $this->callSilent('vendor:publish', ['--tag' => 'dcode-chat-config']);

        $this->comment('Publishing DCode Chat Translations...');
        $this->callSilent('vendor:publish', ['--tag' => 'dcode-chat-translations']);

        $this->info('DCode Chat scaffolding installed successfully.');
    }
}
