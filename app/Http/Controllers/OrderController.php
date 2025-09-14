<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\PurchageNotification;
use Exception;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function createOrder(OrderRequest $orderRequest)
    {
        // try {
            // DB::beginTransaction();
                $totalAmount = collect($orderRequest->songs)->sum('price');
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => strtoupper(Str::random(10)),
                    'total_amount' => $totalAmount,
                    'status' => 'completed',
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
                    'currency' => 'USD',
                    'status' => $transactionStatus ?? 'failed',
                    'payment_method' => $orderRequest->payment_method,
                ]);

                $admin = User::where('role', 'ADMIN')->first();
                if ($admin) {
                    $admin->notify(new PurchageNotification($order,$admin)); // Pass the order data to the notification
                }
            // DB::commit();

            return $this->sendResponse([
                'order' => $order,
                'transaction' => $transaction,
            ], 'Order placed successfully.');
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return $this->sendError('Order creation failed.', ['error' => $e->getMessage()], 500);
        // }
    }
    public function orders(Request $request)
    {
        try {
            $orders = Order::with(['user'])->orderBy('id','desc')->paginate($request->per_page ?? 10);
            return $this->sendResponse($orders, 'Orders retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function userOrders(Request $request)
    {
        try {
            $orders = Order::with(['user'])
                ->where('user_id',auth()->user()->id)->orderBy("id","desc")->paginate($request->per_page??10);
            return $this->sendResponse($orders, 'Orders retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function orderDetails(Request $request,$order_id)
    {
        try {
            $order = Order::find($order_id);
            if(!$order){
                return $this->sendError('Order not found.');
            }
            $orders = OrderDetails::with(['order','user','song','song.artist','song.genre','song.key','song.license','song.type'])
                ->where('order_id',$order_id)->orderBy("id","desc")->get();
            return $this->sendResponse($orders, 'Orders retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
