<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Auth;

class TransactionController extends Controller
{
    public function __construct(){

    }

    public function index(Request $request) {
        $user = Auth::user();

        $query = Transaction::select('transactions.id', 'sender_id', 'senders.name as sender', 'receiver_id', 'receivers.name as receiver', 'amount', 'is_received', 'transactions.created_at', 'transactions.updated_at')
            ->leftJoin('users as senders', 'sender_id', '=', 'senders.id')
            ->leftJoin('users as receivers', 'receiver_id', '=', 'receivers.id');


        switch($request->filter) {
            case 'sent':
                $transactions = $query
                    ->where('transactions.sender_id', $user->id)
                    ->simplePaginate(10)
                    ->withQueryString();  
                
                return view('transactions.index', compact('transactions'));
                break;
            case 'receive':
                $transactions = $query
                    ->where('transactions.receiver_id', $user->id)
                    ->simplePaginate(10)
                    ->withQueryString();  
                
                return view('transactions.index', compact('transactions'));
                break;
            default:
                $transactions = $query
                    ->where('transactions.sender_id', $user->id)
                    ->orwhere(function ($query) {
                        $user = Auth::user();

                        $query->where('transactions.receiver_id', $user->id);
                    })
                    ->simplePaginate(10)
                    ->withQueryString();    

                return view('transactions.index', compact('transactions'));
                break;
        }
    }

    public function create($id) {
        if($id) {
            $contact = User::where('id', $id)->first();

            return view('transactions.create', ['id' => $id, 'name' => $contact->name, 'amount' => 0.0]);
        }

        return redirect('/contacts');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'amount' => 'required',
        ]);

        $user = Auth::user();
        
        if ($validator->fails()) {
            return redirect('transactions/create/'.$request->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        if(floatval($request->amount) > floatval($user->account_value)) {
            return redirect('transactions/create/'.$request->id)
            ->withErrors(['amount' => 'Amount is greater than account value.'])
            ->withInput();
        }

        $validated = $validator->validated();

        Transaction::create([
            'id' => Str::uuid(),
            'sender_id' => $user->id,
            'receiver_id' => $request->id,
            'amount' => floatval($request->amount),
            'is_received' => false
        ]);

        User::where('id', $user->id)
            ->first()
            ->update(['account_value' => floatval($user->account_value) - floatval($request->amount)]);

        return redirect('transactions');
    }

    public function update($id) {
        $transaction = Transaction::where('id', $id)->first();

        if($transaction && !$transaction->is_received) {
            $receiver = User::where('id', $transaction->receiver_id)
            ->first();
            
            User::where('id', $transaction->receiver_id)
                ->update(['account_value' => floatval($receiver->account_value) + floatval($transaction->amount)]);

            Transaction::where('id', $id)->update(['is_received' => true]);
        }

        return redirect('/transactions');
    }

    public function destroy($id) {
        $transaction = Transaction::where('id', $id)->first();

        if(!$transaction->is_received) {
            $user = Auth::user();
            
            User::where('id', $user->id)
            ->first()
            ->update(['account_value' => floatval($user->account_value) + floatval($transaction->amount)]);

            $transaction->delete();
        }

        return redirect('/transactions');
    }
}
