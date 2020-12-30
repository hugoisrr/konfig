<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $users = User::all();
        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('users.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'passwordUser' => 'required',
            'password_confirmation' => 'required'
        ]);

        // Verify is username has been taken
        $userNameExists = User::where('username', '=', $request->input('username'))->first();
        if (!$userNameExists == null)
        {
            return redirect()->back()->with('error', 'Username ist bereits vergeben.');
        }

        $emailExists = User::where('email', '=', $request->input('email'))->first();
        if (!$emailExists == null)
        {
            return redirect()->back()->with('error', 'E-Mail ist bereits vergeben.');
        }

        if (strcmp($request->input('passwordUser'), $request->input('password_confirmation')) !== 0){
            return redirect()->back()->with('error', 'Password und Confirm Password bestätigen ungleich.');
        }

        $user = new User;
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('passwordUser'));

        $user->save();

        return redirect(route('users.index'))->with('success', 'Benutzer erstellt');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $singleUser = User::find($id);
        return view('users.show')->with('user', $singleUser);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'userType' => 'required',
        ]);

        if (!is_null($request->input('passwordUser')))
        {
            if (strcmp($request->input('passwordUser'), $request->input('password_confirmation')) !== 0){
                return redirect()->back()->with('error', 'Password und Confirm Password bestätigen ungleich.');
            }
        }

        try {
            // Find & update User
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->type = $request->input('userType');

            if (!is_null($request->input('passwordUser')))
            {
                $user->password = Hash::make($request->input('passwordUser'));
            }

            $user->save();

            return redirect()->back()->with('success', 'Benutzer aktualisiert.');
        } catch (QueryException $exception){
            return redirect()->back()->with('error', 'Username oder email ist bereits vergeben.');
        }
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->id != 1){
            $user->delete();
            return redirect(route('users.index'));
        }
        return redirect()->back()->with('error', 'Nicht zulassen!');
    }
}
