<?php
// webhook.php
require 'vendor/autoload.php'; // Or path to Razorpay SDK if downloaded manually
use Razorpay\Api\Api;

// Load your Razorpay Key Secret and Webhook Secret from secure configuration
$keyId = getenv('RAZORPAY_KEY_ID');
$keySecret = getenv('RAZORPAY_KEY_SECRET');
$webhookSecret = getenv('RAZORPAY_WEBHOOK_SECRET');

$api = new Api($keyId, $keySecret);

// Get the raw POST body
$webhookBody = file_get_contents('php://input');
$webhookSignature = isset($_SERVER)? $_SERVER : '';

// Parse the JSON payload
$eventData = json_decode($webhookBody, true);

if (empty($webhookSecret)) {
    // Webhook secret not configured, log and exit
    error_log("Webhook secret not set. Cannot verify signature.");
    http_response_code(400); // Bad Request
    exit();
}

try {
    // Verify the webhook signature using the SDK utility
    $api->utility->verifyWebhookSignature($webhookBody, $webhookSignature, $webhookSecret);

    // Signature is valid, process the webhook event
    $eventType = $eventData['event'];
    $payload = $eventData['payload'];

    switch ($eventType) {
        case 'payment.authorized':
            // Handle payment authorized event
            // Update your database to mark the payment as authorized
            // This is useful if auto-capture is off or for specific workflows
            error_log("Payment Authorized: ". $payload['payment']['entity']['id']);
            break;
        case 'payment.captured':
            // Handle payment captured event
            // Update your database to mark the payment as captured
            // Fulfill the order, send confirmation emails, etc.
            error_log("Payment Captured: ". $payload['payment']['entity']['id']);
            break;
        case 'payment.failed':
            // Handle payment failed event
            // Update your database, notify customer, etc.
            error_log("Payment Failed: ". $payload['payment']['entity']['id']. " - ". $payload['payment']['entity']['error_description']);
            break;
        // Add more cases for other events as needed
        default:
            error_log("Unhandled webhook event type: ". $eventType);
            break;
    }

    http_response_code(200); // Acknowledge receipt of the webhook

} catch (Exception $e) {
    // Signature verification failed
    error_log('Razorpay Webhook Signature Verification Failed: '. $e->getMessage());
    http_response_code(400); // Bad Request
    exit();
}
?>