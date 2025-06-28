<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Department;
use App\Http\Requests\UserRegisterRequest;
use App\Classes\AuthClass;
class AuthController extends Controller
{
    protected $authClass;

    public function __construct()
    {
        $this->authClass = new AuthClass();
    }
    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        $departments = Department::where('name', '!=', 'Admin')->get();
        return view('auth.register', compact('departments'));
    }

    /**
     * Handle registration request.
     */
    public function register(UserRegisterRequest $request)
    {
        $user = $this->authClass->createUser($request);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Account created successfully!');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
