<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUpdateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Contact;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function login(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function authenticate(UserLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(AppServiceProvider::HOME)->with('success', 'Berhasil login');
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    
    public function register(): Response
    {
        return Inertia::render('Auth/Register');
    }
    public function storeRegister(UserRegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if(User::where("email", $data["email"])->count() == 1)
        {
            return Redirect::route('register')->with('error', 'Email already exist');
        }

        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        $contact = Contact::create([
            'name' => $data['name'],
            'user_id' => $user->id
        ]);


        return Redirect::route('login')->with('success', 'Silahkan login!');
    }
    public function user(): Response
    {
        return Inertia::render('Contact/Index');
    }

    public function create(): Response
    {
        return Inertia::render('Contact/Create');
    }

    public function edit(int $id): Response
    {
        $user = User::with('contacts')->where('id', $id)->first();
        // dd($user);
        return Inertia::render('Contact/Edit', [
            'user' => $user
        ]);
    }

    public function updateUser(UserRegisterRequest $request, int $id)
    {

        $data = $request->validated();

        $user = User::where('id', $id)->first();
        if(isset($data['name']))
        {
            $user->name = $data['name'];
        }

        if(isset($data['password']))
        {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return Redirect::back()->with('success', 'Berhasil mengubah data');

    }

    public function updateContact(ContactUpdateRequest $request, int $id)
    {
        $contact = Contact::where('user_id', $id)->first();
        if(!$contact)
        {
            return Redirect::route('dashboard')->with('error', 'Data tidak ditemukan');
        }
        $data = $request->validated();
        $contact->fill($data);
        $contact->save();

        return Redirect::route('dashboard')->with('success', 'Data berhasil diubah!');

    }
}
