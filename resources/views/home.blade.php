@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row g-2 justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Account Information</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">Name</div>
                        <div class="col">{{$name}}</div>
                    </div>
                    <div class="row">
                        <div class="col">Email Address</div>
                        <div class="col">{{$email}}</div>
                    </div>
                    <div class="row">
                        <div class="col">Remaining Balance</div>
                        <div class="col">{{$account_value}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
