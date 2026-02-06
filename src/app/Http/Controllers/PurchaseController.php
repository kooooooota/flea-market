<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Item;
use App\Models\PurchasedItem;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * 1. 購入ボタン押下：Stripe決済画面へリダイレクト
     */
    public function checkout(Request $request, $id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $item = Item::findOrFail($id);
        
        // フォーム(name="payment_method")から支払い方法のIDを取得
        $paymentMethodId = $request->input('payment_method');

        // 支払い方法に応じてStripeの画面を切り替える (例: ID 2がコンビニ払いの場合)
        $types = ['card'];
        if ($paymentMethodId == 1) {
            $types[] = 'konbini';
        }

        $session = Session::create([
            'payment_method_types' => $types,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // success_urlは必須パラメータです。スペルに注意してください。
            'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('items.show', $id),
            'metadata' => [
                'item_id'           => $id,
                'user_id'           => Auth::id(),
                'payment_method_id' => $paymentMethodId, // 選択されたIDをStripeへ預ける
            ],
        ]);

        return redirect($session->url);
    }

    /**
     * 2. 決済完了後：DB更新と保存処理
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('items.index')->with('error', 'セッションIDが見つかりません');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Stripeからセッション詳細を取得
            $session = Session::retrieve($sessionId);

            // 決済ステータスが「支払い済み(paid)」または「コンビニ決済受付完了(konbini)」の場合に実行
            if ($session->payment_status === 'paid' || in_array('konbini', $session->payment_method_types)) {
                
                $itemId = $session->metadata->item_id;
                $userId = $session->metadata->user_id;
                $paymentMethodId = $session->metadata->payment_method_id;

                $user = User::findOrFail($userId);
                $item = Item::findOrFail($itemId);

                // 住所の取得ロジック
                // セッションに一時的な配送先があるか確認
                $shippingAddress = $request->session()->get('shipping_address');

                if (!$shippingAddress) {
                    // セッションにない場合は profiles テーブルから取得
                    $profile = Profile::where('user_id', $user->id)->first();

                    $shippingAddress = [
                        'zip_code' => $profile->zip_code ?? '',
                        'address'  => $profile->address ?? '',
                        'building' => $profile->building ?? '',
                    ];
                }

                // DB保存（トランザクション）
                DB::transaction(function () use ($item, $user, $shippingAddress, $paymentMethodId, $request) {
                    // ① itemsテーブルのsoldをtrueにする
                    $item->update(['sold' => true]);

                    // ② purchased_itemsテーブルに保存
                    PurchasedItem::create([
                        'user_id'           => $user->id,
                        'item_id'           => $item->id,
                        'payment_method_id' => $paymentMethodId,
                        'shipping_address'  => implode(' ', array_filter($shippingAddress)),
                    ]);

                    // 使い終わった住所セッションを削除
                    $request->session()->forget('shipping_address');
                });

                return redirect()->route('items.index')->with('success', '購入が完了しました！');
            }
        } catch (\Exception $e) {
            return "決済処理中にエラーが発生しました: " . $e->getMessage();
        }

        return redirect('/')->with('error', '決済に失敗しました');
    }
}
