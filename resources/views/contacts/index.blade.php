@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Contacts</h1>
        <div class="d-grid d-md-flex justify-content-md-end">
            <a href="/contacts/create" class="btn btn-primary">Add Contact</a>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Contact Name</th>
                    <th scope="col">Contact Email</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($contacts->isEmpty()) 
                    <tr>
                        <th colspan="4">No contact found</th>
                    </tr>
                @endif

                @foreach($contacts as $contact)
                    <tr>
                        <th scope="row">{{$contact->id}}</th>
                        <td>{{$contact->name}}</td>
                        <td>{{$contact->email}}</td>
                        <td>
                            <div class="d-grid gap-2 d-flex justify-content-start">
                                <a href="/transactions/create/{{$contact->id}}" class="btn btn-sm btn-primary">Send Money</a>
                                <form action="/contacts/{{$contact->id}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" type="button" onclick="return confirm('Are you sure you want to delete this contact?')">Remove</button>
                                </form>
                            </div>
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