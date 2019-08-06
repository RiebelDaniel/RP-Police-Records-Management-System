<?php

namespace App\Http\Controllers;

use App\Rank;
use App\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        $users = User::paginate(30);
        return view('User.userlist',[
            'users' => $users
        ]);
    }


    public function create()
    {
        $roles = Role::all();

        return view('User.create',[
            'roles' => $roles
        ]);
    }


    public function store()
    {
        $validated = request()->validate([
            'username' => ['required','min:3','unique:users,username'],
            'UID' => ['required','numeric','unique:users,UID'],
            'forum_id' => ['required','numeric'],
            'rank_id' => ['required','numeric']
        ]);
        $validated['creator_id'] = auth()->user()->id;

        $random = Str::random(8);
        session()->flash('message', "Das Password des neuen Benutzers ist: ".$random);
        $validated['password'] = Hash::make($random);


        $newUser = User::create($validated);

        return redirect('/user/'.$newUser->id);
}


    public function show(User $user)
    {



        return view('User.show',[
            'user' => $user,
            'points' => $user->getPoints()
        ]);
    }


    public function edit(User $user)
    {
        return redirect('/');
    }

    public function update(User $user)
    {
        return redirect('/');
    }


    public function destroy(User $user)
    {
        if ($user->id == auth()->user()->id){
            session()->flash('message','Lass das! Sonst werde ich sauer! (╯°□°）╯︵ ┻━┻ ');
            return back();
        }
        $user->delete();

        return redirect('/');
    }
}
