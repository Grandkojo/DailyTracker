<?php

namespace App\Classes;

use App\Models\Department;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createUser(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department,
            'position' => $request->position,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'support_team',
        ]);

        return $user;
    }
}
