@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="/transactions">
            @csrf
            <input type="hidden" name="id" value="{{$id}}"/>
            <div class="mb-3 row">
                <label for="staticName" class="col-sm-2 col-form-label">Receiver Name</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="staticName" name="name" value="{{$name}}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputAmount" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="inputAmount" name="amount" value="{{old('amount')}}">
                    @error('amount')
                    <div id="inputAmountFeedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Post Transaction</button>
        </form>
    </div>
@endsection