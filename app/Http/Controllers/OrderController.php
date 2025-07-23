<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    public function createOrder(OrderRequest $orderRequest)
    {
        try {
            DB::beginTransaction();
                $totalAmount = collect($orderRequest->songs)->sum('price');
                $orderStatus = $orderRequest->order_status;
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => strtoupper(Str::random(10)),
                    'total_amount' => $totalAmount,
                    'status' => $orderStatus,
                ]);
                foreach ($orderRequest->songs as $song) {
                    OrderDetails::create([
                        'order_id' => $order->id,
                        'song_id' => $song['song_id'],
                        'price' => $song['price'],
                        'total' => $totalAmount,
                    ]);
                }
                $transactionStatus = $order->status === 'completed' ? 'success' : 'pending';
                $transaction = Transaction::create([
                    'order_id' => $order->id,
                    'amount' => $totalAmount,
                    'currency' => 'GBP',
                    'status' => $transactionStatus ?? 'failed',
                    'payment_method' => $orderRequest->payment_method,
                ]);
            DB::commit();
            return $this->sendResponse([
                'order' => $order,
                'transaction' => $transaction,
            ], 'Order placed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError('Order creation failed.', ['error' => $e->getMessage()], 500);
        }
    }
    public function orders()
    {
        try {
            $orders = Order::with(['user','orderDetails','orderDetails.song','orderDetails.order','orderDetails.order.user'])->get();
            return $this->sendResponse($orders, 'Orders retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
