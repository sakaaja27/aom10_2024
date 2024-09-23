<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Set up Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');

        // Handle webhook notification
        $notification = new Notification();
        $transaction = $notification->transaction;  

        // Process the transaction status update
        // ...

        return response()->json(['message' => 'Webhook received successfully']);
    }
}
