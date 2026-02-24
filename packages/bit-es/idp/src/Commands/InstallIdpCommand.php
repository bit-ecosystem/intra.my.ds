<?php

declare(strict_types=1);

namespace Bites\Idp\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\ClientRepository;

class InstallIdpCommand extends Command
{
    protected $signature = 'idp:install {--create-client}';

    protected $description = 'Install Passport and optionally create a sample OAuth client for testing.';

    public function handle(): void
    {
        $this->info('Running migrations...');
        $this->call('migrate');

        $this->info('Installing Passport keys...');
        $this->call('passport:install');

        if ($this->option('create-client')) {
            $clientRepository = new ClientRepository;
            $client = $clientRepository->createAuthCodeClient(null, 'sample-client', 'http://localhost:8001/callback');
            $this->info('Created client: ID='.$client->id.' Secret='.$client->secret);
        }

        $this->info('IdP install complete.');
    }
}
