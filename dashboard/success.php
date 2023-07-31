<?php
// Check if the payment was successful
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    echo 'Payment successful! Thank you for your purchase.';
} else {
    echo 'Payment failed. Please contact customer support for assistance.';
}
?>
