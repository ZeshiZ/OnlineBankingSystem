<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use Auth;

class ContactController extends Controller
{
    public function __construct() {

    }

    public function index() {
        $user = Auth::user();

        $contacts = DB::table('contacts')
            ->select('users.id', 'users.name', 'users.email')
            ->join('users', 'users.id', '=', 'contacts.contact_id')
            ->where('contacts.user_id', '=', $user->id)
            ->simplePaginate(10)
            ->withQueryString();

        return view('contacts.index', compact('contacts'));
    }

    public function create(Request $request) {
        $user = Auth::user();

        $userContacts = DB::table('contacts')
            ->where('user_id', '=', $user->id);

        $query = DB::table('users')
            ->leftJoinSub($userContacts, 'user_contacts', function($join) {
                $join->on('users.id', '=', 'user_contacts.contact_id');
            })
            ->select('users.id', 'users.name', 'users.email')
            ->where('users.id', '!=', $user->id)
            ->whereNull('user_contacts.contact_id');

        if($request->has('search') && Str::of($request->search)->trim() != '') {
            $query->where('users.name', 'like', '%'.$request->search.'%');
        }

        $contacts = $query->simplePaginate(10)
            ->withQueryString();        

        return view('contacts.create', compact('contacts'));
    }

    public function store(Request $request) {
        $user = Auth::user();

        //DB::insert('insert into contacts (user_id, contact_id) values (?, ?)', [$user->id, $request->id]);

        Contact::create([
            'user_id' => $user->id,
            'contact_id' => $request->id
        ]);

        return redirect('/contacts');
    }

    public function destroy($id) {
        $user = Auth::user();

        Contact::where('user_id', $user->id)
            ->where('contact_id', $id)
            ->delete();

        return redirect('/contacts');
    }
}
