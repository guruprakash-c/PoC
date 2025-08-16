<?php
// Assuming $api is already initialized with your Key ID and Key Secret
try {
    $api->utility->verifyPaymentSignature(array(
        'razorpay_order_id'    => $razorpayOrderId,    // Your order ID from your database
        'razorpay_payment_id'  => $razorpayPaymentId,  // Received from Razorpay Checkout
        'razorpay_signature'   => $razorpaySignature   // Received from Razorpay Checkout
    ));

    // Signature is valid, payment is successful and authentic
    // Proceed to update your database, fulfill the order, etc.
    echo "Payment successful and verified!";

} catch (Exception $e) {
    // Signature verification failed
    error_log('Razorpay Signature Verification Failed: '. $e->getMessage());
    echo "Payment failed or tampered. Verification failed.";
    // Handle fraudulent attempt or log for investigation
}
?>