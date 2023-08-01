function payWithFlutterwave() {
    const name = document.getElementById('name').value;
    const emailInput = document.getElementById('emailInput');
    const email = emailInput.value;
    const amount = document.getElementById('amount').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const currency = document.getElementById('currency').value;

    // Replace with the actual sub-account ID for the Ghanaian merchant
    const ghanaianSubAccountID = 'RS_93A21110ADCE696B196F295488BDAF92';

    if (currency === 'GHS') {
        FlutterwaveCheckout({
            public_key: 'FLWPUBK_TEST-425610b01cc431cf7bbb04ac39fe5cc7-X',
            tx_ref: Date.now(),
            amount: amount,
            currency: 'GHS', // Manually set the currency for Ghanaian Cedi
            payment_type: paymentMethod,
            customer: {
                email: email,
                name: name,
            },
            subaccounts: [
                {
                    id: ghanaianSubAccountID,
                    transaction_charge_type: 'flat_subaccount',
                    transaction_charge: 0,
                },
            ],
            callback: function(response) {
                console.log(response);

                async function sendDataToPhp(url) {
                    const formData = new FormData();
                    formData.append('amount', response.amount);
                    formData.append('email', response.customer.email);
                    formData.append('transaction_id', response.transaction_id);
                    formData.append('tx_ref', response.tx_ref);
                    formData.append('status', response.status);
                    formData.append('created_at', response.created_at);
                    formData.append('flutterwave_ref', response.flw_ref);
                    formData.append('currency', response.currency);
                    let urlEncodedData = new URLSearchParams(formData).toString();
                    console.log(urlEncodedData);

                    try {
                        let respo = await fetch(url, {
                            method: 'POST',
                            body: formData,
                        });
                        if (respo.ok) {
                            let data = await respo.json();
                            console.log(data);
                            console.log('response received');
                        } else {
                            console.log('Failed to send data to PHP.');
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }

                // Sending the data to the database;
                sendDataToPhp('./webhook.php');
            },
        });
    } else if (currency === 'NGN') {
        FlutterwaveCheckout({
            public_key: 'FLWPUBK_TEST-ad933cb286ea9eac48939472e44877f7-X',
            tx_ref: Date.now(),
            amount: amount,
            currency: 'NGN', // Manually set the currency for Nigerian Naira
            payment_type: paymentMethod,
            customer: {
                email: email,
                name: name,
            },
            callback: function(response) {
                console.log(response);

                async function sendDataToPhp(url) {
                    const formData = new FormData();
                    formData.append('amount', response.amount);
                    formData.append('email', response.customer.email);
                    formData.append('transaction_id', response.transaction_id);
                    formData.append('tx_ref', response.tx_ref);
                    formData.append('status', response.status);
                    formData.append('created_at', response.created_at);
                    formData.append('flutterwave_ref', response.flw_ref);
                    formData.append('currency', response.currency);
                    let urlEncodedData = new URLSearchParams(formData).toString();
                    console.log(urlEncodedData);

                    try {
                        let respo = await fetch(url, {
                            method: 'POST',
                            body: formData,
                        });
                        if (respo.ok) {
                            let data = await respo.json();
                            console.log(data);
                            console.log('response received');
                        } else {
                            console.log('Failed to send data to PHP.');
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }

                // Sending the data to the database;
                sendDataToPhp('./webhook.php');
            },
        });
    } else {
        // Handle unsupported currency selection
        alert('Invalid currency selection.');
    }
}