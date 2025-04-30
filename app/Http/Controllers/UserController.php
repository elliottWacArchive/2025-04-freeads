<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        // pour que l'utilisateur accès que à son propre profil
        $this->middleware('auth')->except(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        event(new Registered($user));
        
        auth()->login($user);
    
        return redirect()->route('verification.notice')
            ->with('success', 'Compte créé avec succès ! Veuillez vérifier votre email pour activer votre compte.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if ($id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ($id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        if ($id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $user = User::findOrFail($id);
        $data = [];
        
        if ($request->has('name')) {
            $data['name'] = $request->name;
        }
        
        if ($request->has('email')) {
            $data['email'] = $request->email;
            if ($user->email != $request->email) {
                $data['email_verified_at'] = null;
            }
        }
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        if (isset($data['email_verified_at']) && $data['email_verified_at'] === null) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('verification.notice')
                ->with('success', 'Profil mis à jour avec succès. Veuillez vérifier votre nouvelle adresse email.');
        }
        
        return redirect()->route('users.show', $user->id)
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id != Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $user = User::findOrFail($id);
        Auth::logout();
        
        $user->delete();
        
        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès.');
    }
}