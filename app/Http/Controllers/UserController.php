<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role != 'admin') {
            abort(403, 'You Dont Have Access');
        }

        if ($request->has('search') && $request->search !== null) {
            $search = strtolower($request->search);
            $users = User::whereRaw('LOWER(name) LIKE ?', ['%'.$search.'%'])
                ->paginate(10)
                ->appends($request->only('search'));
        } else {
            $users = User::paginate(10);
        }

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;

        if(User::where('email', $user->email)->exists()) {
            return redirect()->route('user.index')->with('error', 'Email already exists!');
        }

        $user->save();

        return redirect()->route('user.index')->with('message', 'User created successfully!');
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('user.index')->with('message', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->id == Auth::user()->id) {
            return redirect()->route('user.index')->with('error', 'You cannot delete your own account!');
        }
        $user->delete();

        return redirect()->route('user.index');
    }
}
