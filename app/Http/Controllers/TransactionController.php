<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transactions(Request $request)
    {
        try {
            $transactions = Transaction::with(['order', 'order.user'])
            ->orderBy('id','desc')
            ->paginate($request->per_page ?? 10);
            return $this->sendResponse($transactions, 'Transactions retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve transactions: ' . $e->getMessage(), [], 500);
        }
    }
}
