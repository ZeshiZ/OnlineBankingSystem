@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Transactions</h1>
        <div class="d-grid justify-content-end mb-3">
            <form action="/transactions" method="get">
                <div class="d-grid gap-2 d-flex justify-content-end">
                    <select name="filter" class="form-select form-select-lg">
                        <option value="all" @if(request()->get('filter') == null || request()->get('filter') == 'all') selected @endif>All</option>
                        <option value="sent" @if(request()->get('filter') == 'sent') selected @endif>Sent</option>
                        <option value="receive" @if(request()->get('filter') == 'receive') selected @endif>Receive</option>
                    </select>
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Sender</th>
                    <th scope="col">Receiver</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Type</th>
                    <th scope="col">Sent Date</th>
                    <th scope="col">Received Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($transactions->isEmpty())
                    <tr>
                        <th colspan="7">No transaction found.</th>
                    </tr>       
                @endif
                @foreach($transactions as $transaction)
                    <tr>
                        <th scope="row">{{$transaction->sender}}</th>
                        <th scope="row">{{$transaction->receiver}}</th>
                        <td>{{$transaction->amount}}</td>
                        <td>
                            @if(auth()->user()->id == $transaction->sender_id)
                                Sent
                            @else
                                Receive
                            @endif
                        </td>
                        <td>{{$transaction->created_at}}</td>
                        <td>{{$transaction->updated_at}}</td>
                        <td>
                            @if(auth()->user()->id == $transaction->sender_id)
                                <form action="/transactions/{{$transaction->id}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" onclick="return confirm('Are you sure you want to cancel this transaction?')" class="btn btn-primary btn-sm @if(\Carbon\Carbon::now()->diffInMinutes($transaction->created_at) > 120 || $transaction->is_received) disabled @endif">Cancel</button>
                                </form>
                            @else
                                <form action="/transactions/{{$transaction->id}}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm @if($transaction->is_received) disabled @endif">Receive</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>    
        </table>

        <div class="d-grid d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection