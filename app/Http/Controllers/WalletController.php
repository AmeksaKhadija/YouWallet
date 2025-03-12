<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class WalletController extends Controller
{
    //
    public function stokage(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $amount = $request->input('amount');

            if ($amount <= 0) {
                return response()->json([
                    'message' => 'amount non validée '
                ], 400);
            }

            $user->wallet->balance += $amount;
            $user->wallet->save();

            Transaction::create([
                'sender_wallet_id' => $user->wallet->id,
                'recipient_wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'type' => 'stoker'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'stokage avec success',
                'balance' => $user->wallet->balance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'stokage failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recevoire(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $amount = $request->input('amount');

            if ($amount <= 0) {
                return response()->json([
                    'message' => 'Invalid amount'
                ], 400);
            }

            if ($user->wallet->balance < $amount) {
                return response()->json([
                    'message' => 'Insufficient balance'
                ], 400);
            }

            $user->wallet->balance -= $amount;
            $user->wallet->save();

            $transaction = Transaction::create([
                'sender_wallet_id' => $user->wallet->id,
                'recipient_wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'type' => 'recevoire'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'recevoire successful',
                'balance' => $user->wallet->balance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'recevoire failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function envoyer(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $amount = $request->input('amount');
            $wallet_id = $request->input('recipient_id');
            $wallet = Wallet::find($wallet_id);

            if (!$wallet) {
                return response()->json([
                    'message' => 'Wallet not found'
                ], 404);
            }

            if ($amount <= 0) {
                return response()->json([
                    'message' => 'Invalid amount'
                ], 400);
            }

            if ($user->wallet->balance < $amount) {
                return response()->json([
                    'message' => 'Insufficient balance'
                ], 400);
            }

            $user->wallet->balance -= $amount;
            $user->wallet->save();

            $wallet->balance += $amount;
            $wallet->save();

            $transaction = Transaction::create([
                'sender_wallet_id' => $user->wallet->id,
                'recipient_wallet_id' => $wallet->id,
                'amount' => $amount,
                'type' => 'envoyer'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Transfer successful',
                'balance' => $user->wallet->balance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Transfer failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
