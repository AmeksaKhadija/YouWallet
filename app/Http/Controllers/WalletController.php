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
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $amount = $request->input('amount');

            if ($amount <= 0) {
                return response()->json([
                    'message' => 'amount non validée '
                ], 422);
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

    public function retrait(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $amount = $request->input('amount');

            if ($amount <= 0) {
                return response()->json([
                    'message' => 'impossible de retirer cet argent'
                ], 422);
            }

            if ($user->wallet->balance < $amount) {
                return response()->json([
                    'message' => 'vous avez moins de cet argent que vous devez envoyer dans votre wallet'
                ], 422);
            }

            $user->wallet->balance -= $amount;
            $user->wallet->save();

            Transaction::create([
                'sender_wallet_id' => $user->wallet->id,
                'recipient_wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'type' => 'retrait'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'retrait avec success',
                'balance' => $user->wallet->balance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'retrait failed',
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
            $receiver_id = $request->input('recipient_id');
            $reciever_wallet = Wallet::find($receiver_id);

            if ($amount <= 0) {
                return response()->json([
                    'message' => 'impossible d envoyer un amount moins de 0'
                ], 422);
            }

            
            if ($user->wallet->balance < $amount) {
                return response()->json([
                    'message' => 'Balance que vous avez moins de amount que vous devez envoyer'
                ], 422);
            }
            
            if (!$reciever_wallet) {
                return response()->json([
                    'message' => 'cette wallet not found'
                ], 404);
            }
            
            $user->wallet->balance -= $amount;
            $user->wallet->save();

            $reciever_wallet->balance += $amount;
            $reciever_wallet->save();

            Transaction::create([
                'sender_wallet_id' => $user->wallet->id,
                'recipient_wallet_id' => $reciever_wallet->id,
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
