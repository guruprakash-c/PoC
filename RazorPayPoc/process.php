<?php
// Example of try-catch for API calls
require 'vendor/autoload.php';
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError; // Example of a specific Razorpay SDK exception

$keyId = getenv('RAZORPAY_KEY_ID');
$keySecret = getenv('RAZORPAY_KEY_SECRET');
$api = new Api($keyId, $keySecret);

try {
    // Code that might throw a Razorpay SDK exception, e.g., order creation
    $order = $api->order->create();

    echo "Order created successfully: ". $order['id'];

} catch (BadRequestError $e) {
    // Handle specific Razorpay API bad request errors
    error_log("Razorpay Bad Request Error: ". $e->getMessage(). " - Code: ". $e->getCode());
    echo "Payment request invalid. Please check details and try again.";
} catch (Throwable $e) {
    // Catch any other general exceptions or errors
    error_log("An unexpected error occurred: ". $e->getMessage());
    echo "An unexpected error occurred. Please try again later.";
} finally {
    // Optional: Code that always runs, regardless of whether an exception was thrown
    // e.g., closing database connections or releasing resources
}
?>