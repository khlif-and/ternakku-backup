<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentReceiptController extends Controller
{
    public function index()
    {
        $paymentReceipts = [];

        return view('admin.qurban.payment_receipt.index', compact('paymentReceipts'));
    }

    public function create()
    {
        return view('admin.qurban.payment_receipt.create');
    }
}
