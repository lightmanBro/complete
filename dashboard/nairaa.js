// Function to initiate payment
async function initiatePayment(currency) {
    const name = document.getElementById('name').value;
    const email = document.getElementById('emailInput').value;
    const amount = document.getElementById('amount').value;

    // Replace with your actual API keys
    const publicKey = 'FLWPUBK_TEST-425610b01cc431cf7bbb04ac39fe5cc7-X';

    // API endpoint to create payment
    const apiUrl = 'https://api.flutterwave.com/v3/payments';

    // Payment data to be sent to the API
    const paymentData = {
        tx_ref: Date.now().toString(), // Replace with your unique transaction reference
        amount: amount,
        currency: 'NGN',
        payment_type: 'card', // Use 'card' for card payments
        customer: {
            email: email,
            name: name,
        },
        public_key: publicKey,
    };

    try {
        // Make API request to create payment
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${publicKey}`,
            },
            body: JSON.stringify(paymentData),
        });

        // Parse the API response
        const responseData = await response.json();

        // Check if the payment was initiated successfully
        if (responseData.status === 'success') {
            // Redirect user to the payment page
            window.location.href = responseData.data.link;
        } else {
            // Handle payment initiation failure
            alert('Payment initiation failed. Please try again later.');
        }
    } catch (error) {
        // Handle API request error
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
        console.log('Error Response:', await error.json());
    }
    
}

function payWithFlutterwave() {
    const currency = document.getElementById('currency').value;
    initiatePayment(currency);
}
