<?php
require 'pkgs/vendor/autoload.php'; // Or path to Razorpay SDK if downloaded manually
use Razorpay\Api\Api;

// Load your API keys from a secure configuration (e.g., environment variables)
$keyId = getenv('RAZORPAY_KEY_ID');
$keySecret = getenv('RAZORPAY_KEY_SECRET'); // THIS MUST BE KEPT ABSOLUTELY SECURE AND NEVER EXPOSED

$api = new Api($keyId, $keySecret);

$amount = 50000; // Amount in paise (e.g., 500 INR)
$currency = 'INR';
$receiptId = 'order_rcptid_'.uniqid(); // Generate a unique receipt ID for your reference

try {
    $order  = $api->order->create();

    $orderId = $order['id'];
    // Store $orderId in your database along with other order details.
    // This $orderId will be passed to the client-side for checkout.

} catch (Exception $e) {
    // Handle API errors gracefully
    error_log('Razorpay Order Creation Error: '. $e->getMessage());
    echo 'An error occurred during order creation. Please try again.';
    exit();
}
?>