// Initialize Stripe with the public key
const stripe = Stripe(process.env.MIX_STRIPE_KEY);

// Handle the form submission for Stripe payments
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Disable the submit button to prevent multiple submissions
            document.getElementById('submit-button').disabled = true;
            
            // Create a payment method and confirm the payment
            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: document.getElementById('cardholder-name').value
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    // Show error to your customer
                    showError(result.error.message);
                } else {
                    // The payment succeeded!
                    form.submit();
                }
            });
        });
    }
});

// Show an error message
function showError(message) {
    const errorElement = document.getElementById('card-errors');
    errorElement.textContent = message;
    document.getElementById('submit-button').disabled = false;
} 