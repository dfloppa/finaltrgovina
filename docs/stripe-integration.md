# Stripe Integration

This document provides information on how to set up and use the Stripe integration in the e-commerce application.

## Setup

1. Create a Stripe account at [stripe.com](https://stripe.com) if you don't have one already.
2. Get your API keys from the Stripe Dashboard.
3. Add the following environment variables to your `.env` file:

```
STRIPE_KEY=your_publishable_key
STRIPE_SECRET=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_secret
```

## Testing the Integration

You can test the Stripe integration using the provided Artisan command:

```
php artisan stripe:test
```

This command will create a test payment intent and checkout session to verify that your Stripe integration is working correctly.

## Setting Up Webhooks

Webhooks allow Stripe to notify your application when events happen in your account, such as successful payments.

### Local Development

For local development, you can use the Stripe CLI to forward webhook events to your local environment:

1. Install the Stripe CLI using the provided Artisan command:

```
php artisan stripe:install-cli
```

2. Log in to your Stripe account:

```
stripe login
```

3. Start forwarding webhook events to your local server:

```
stripe listen --forward-to http://localhost:8000/stripe/webhook
```

### Production

For production, you need to set up a webhook endpoint in the Stripe Dashboard:

1. Go to the Stripe Dashboard > Developers > Webhooks.
2. Click "Add endpoint".
3. Enter your webhook URL: `https://yourdomain.com/stripe/webhook`.
4. Select the events you want to receive (at minimum, select `checkout.session.completed`).
5. Click "Add endpoint".
6. Copy the "Signing secret" and add it to your `.env` file as `STRIPE_WEBHOOK_SECRET`.

## Checkout Flow

1. User adds products to their cart.
2. User proceeds to checkout and fills in their shipping and billing information.
3. User selects Stripe as the payment method and clicks "Place Order".
4. The application creates an order in the database with a status of "pending".
5. The application creates a Stripe Checkout Session and redirects the user to the Stripe-hosted checkout page.
6. User enters their payment information on the Stripe checkout page.
7. After successful payment, Stripe redirects the user back to the success page.
8. Stripe also sends a webhook event to notify the application of the successful payment.
9. The application updates the order status to "processing" upon receiving the webhook event.

## Testing with Stripe Test Cards

You can use the following test card numbers to test different scenarios:

- Successful payment: `4242 4242 4242 4242`
- Failed payment: `4000 0000 0000 0002`
- Requires authentication: `4000 0025 0000 3155`

For all test cards, you can use:
- Any future expiration date
- Any 3-digit CVC
- Any postal code

## Troubleshooting

If you encounter issues with the Stripe integration, check the following:

1. Verify that your API keys are correct in the `.env` file.
2. Check the Laravel logs for any error messages.
3. Verify that the webhook endpoint is correctly set up and receiving events.
4. Test the integration using the `stripe:test` command.
5. Use the Stripe CLI to debug webhook events.

For more information, refer to the [Stripe API documentation](https://stripe.com/docs/api). 