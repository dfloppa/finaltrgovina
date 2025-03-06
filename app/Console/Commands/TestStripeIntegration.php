<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;

class TestStripeIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Stripe integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Stripe integration...');
        
        // Check if Stripe keys are set
        $stripeKey = config('services.stripe.key');
        $stripeSecret = config('services.stripe.secret');
        
        if (empty($stripeKey) || empty($stripeSecret)) {
            $this->error('Stripe keys are not set in your .env file.');
            $this->info('Please add the following to your .env file:');
            $this->info('STRIPE_KEY=your_stripe_publishable_key');
            $this->info('STRIPE_SECRET=your_stripe_secret_key');
            $this->info('STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret');
            return Command::FAILURE;
        }
        
        // Set Stripe API key
        Stripe::setApiKey($stripeSecret);
        
        try {
            // Create a test payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => 1000, // $10.00
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => 'Test payment',
            ]);
            
            $this->info('Successfully created a test payment intent!');
            $this->info('Payment Intent ID: ' . $paymentIntent->id);
            
            // Create a test checkout session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Test Product',
                        ],
                        'unit_amount' => 1000, // $10.00
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/checkout/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/checkout/cancel'),
            ]);
            
            $this->info('Successfully created a test checkout session!');
            $this->info('Checkout Session ID: ' . $session->id);
            $this->info('Checkout URL: ' . $session->url);
            
            $this->info('Stripe integration test completed successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Stripe integration test failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 