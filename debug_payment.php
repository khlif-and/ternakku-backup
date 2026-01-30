<?php
use App\Models\QurbanPayment;

$payment = QurbanPayment::latest()->first();

if ($payment) {
    echo "Payment ID: " . $payment->id . "\n";
    echo "Qurban Customer ID: " . $payment->qurban_customer_id . "\n";
    echo "Livestock ID: " . $payment->livestock_id . "\n";
    
    if ($payment->qurbanCustomer) {
        echo "Customer Name: " . $payment->qurbanCustomer->name . "\n";
    } else {
        echo "Customer Relation is NULL\n";
    }

    if ($payment->livestock) {
        echo "Livestock Eartag: " . $payment->livestock->eartag . "\n";
    } else {
        echo "Livestock Relation is NULL\n";
    }
} else {
    echo "No payments found.\n";
}
