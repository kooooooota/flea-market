<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // イベントの種類に応じて処理を分ける
        if ($event->type === 'checkout.session.async_payment_succeeded') {
            $session = $event->data->object; // セッション情報
            $sessionId = $session->id; // これでDBを検索して「支払い済み」にする
            
            // TODO: DB更新処理やメール送信など
        }

        return response()->json(['status' => 'success']);
    }
}
