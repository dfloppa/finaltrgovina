<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');
        
        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            // Invalid payload
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }
        
        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                return $this->handleCheckoutSessionCompleted($event->data->object);
                
            case 'payment_intent.succeeded':
                return $this->handlePaymentIntentSucceeded($event->data->object);
                
            case 'payment_intent.payment_failed':
                return $this->handlePaymentIntentFailed($event->data->object);
                
            default:
                Log::info('Unhandled Stripe event: ' . $event->type);
                return response()->json(['status' => 'success']);
        }
    }
    
    protected function handleCheckoutSessionCompleted($session)
    {
        // Find the order by the session ID
        $order = Order::where('payment_id', $session->id)->first();
        
        if (!$order) {
            Log::error('Order not found for Stripe session: ' . $session->id);
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        // Update order status
        $order->payment_status = Order::PAYMENT_STATUS_PAID;
        $order->status = Order::STATUS_PROCESSING;
        $order->save();
        
        Log::info('Payment completed for order: ' . $order->order_number);
        
        return response()->json(['status' => 'success']);
    }
    
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment intent succeeded: ' . $paymentIntent->id);
        return response()->json(['status' => 'success']);
    }
    
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        // Find the order by metadata
        if (isset($paymentIntent->metadata->order_id)) {
            $order = Order::find($paymentIntent->metadata->order_id);
            
            if ($order) {
                $order->payment_status = Order::PAYMENT_STATUS_FAILED;
                $order->save();
                
                Log::error('Payment failed for order: ' . $order->order_number);
            }
        }
        
        return response()->json(['status' => 'success']);
    }
} 