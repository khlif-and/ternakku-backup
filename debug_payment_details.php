<?php
use App\Models\QurbanPayment;
use App\Models\QurbanCustomer;
use App\Models\Livestock;

$payment = QurbanPayment::latest()->first();

if ($payment) {
    echo "Payment ID: " . $payment->id . "\n";
    
    $customer = QurbanCustomer::find($payment->qurban_customer_id);
    if ($customer) {
        echo "Customer Data: " . json_encode($customer->toArray()) . "\n";
    } else {
        echo "Customer ID {$payment->qurban_customer_id} NOT FOUND\n";
    }

    $livestock = Livestock::find($payment->livestock_id);
    if ($livestock) {
        echo "Livestock Data: " . json_encode($livestock->toArray()) . "\n";
    } else {
        echo "Livestock ID {$payment->livestock_id} NOT FOUND\n";
    }
}
