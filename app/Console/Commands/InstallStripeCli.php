<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class InstallStripeCli extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:install-cli';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Stripe CLI for testing webhooks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing Stripe CLI...');
        
        // Check the operating system
        $os = PHP_OS;
        
        if (strpos($os, 'WIN') !== false) {
            // Windows
            $this->info('Detected Windows OS');
            $this->info('Please install Stripe CLI manually from: https://stripe.com/docs/stripe-cli');
            $this->info('After installation, run: stripe login');
            $this->info('Then run: stripe listen --forward-to http://localhost:8000/stripe/webhook');
        } elseif (strpos($os, 'Darwin') !== false) {
            // macOS
            $this->info('Detected macOS');
            $this->info('Installing via Homebrew...');
            $result = Process::run('brew install stripe/stripe-cli/stripe');
            
            if ($result->successful()) {
                $this->info('Stripe CLI installed successfully!');
                $this->info('Please run: stripe login');
                $this->info('Then run: stripe listen --forward-to http://localhost:8000/stripe/webhook');
            } else {
                $this->error('Failed to install Stripe CLI. Please install manually.');
                $this->info('Visit: https://stripe.com/docs/stripe-cli');
            }
        } elseif (strpos($os, 'Linux') !== false) {
            // Linux
            $this->info('Detected Linux OS');
            $this->info('Installing...');
            
            $result = Process::run('curl -s https://packages.stripe.dev/api/security/keypair/stripe-cli-gpg/public | gpg --dearmor | sudo tee /usr/share/keyrings/stripe.gpg > /dev/null');
            
            if ($result->successful()) {
                Process::run('echo "deb [signed-by=/usr/share/keyrings/stripe.gpg] https://packages.stripe.dev/stripe-cli-debian-local stable main" | sudo tee -a /etc/apt/sources.list.d/stripe.list');
                Process::run('sudo apt update');
                $result = Process::run('sudo apt install stripe');
                
                if ($result->successful()) {
                    $this->info('Stripe CLI installed successfully!');
                    $this->info('Please run: stripe login');
                    $this->info('Then run: stripe listen --forward-to http://localhost:8000/stripe/webhook');
                } else {
                    $this->error('Failed to install Stripe CLI. Please install manually.');
                    $this->info('Visit: https://stripe.com/docs/stripe-cli');
                }
            } else {
                $this->error('Failed to add Stripe repository. Please install manually.');
                $this->info('Visit: https://stripe.com/docs/stripe-cli');
            }
        } else {
            // Unknown OS
            $this->error('Unknown operating system. Please install Stripe CLI manually.');
            $this->info('Visit: https://stripe.com/docs/stripe-cli');
        }
        
        return Command::SUCCESS;
    }
} 