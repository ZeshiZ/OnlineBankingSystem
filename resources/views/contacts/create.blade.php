@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Add Contact</h1>
        <form action="/contacts/create">
            <div class="input-group">
                <input name="search" type="text" class="form-control" placeholder="Search name" value="{{ request()->get('search') }}">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Contact Name</th>
                    <th>Contact Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($contacts->isEmpty()) 
                    <tr>
                        <th scope="row">No record found</th>
                    </tr>
                @endif

                @foreach($contacts as $contact)
                    <tr>
                        <th scope="row">{{$contact->id}}</th>
                        <td>{{$contact->name}}</td>
                        <td>{{$contact->email}}</td>
                        <td>
                            <form method="post" action="/contacts/store">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{$contact->id}}" name="id">
                                <button type="submit" class="btn btn-primary">Add to Contacts</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="d-grid d-flex justify-content-center">
            {{ $contacts->links() }}
        </div>
    </div>
@endsection