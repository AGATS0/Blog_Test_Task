<?php

namespace App\Actions\Auth;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterAction 
{

    /**
     * register new user.
     *
     * @param  string $name 
     * @param  string $email
     * @param  string $password
     * @return array{user:User,accessToken:string}     
     */
    public function execute(string $name, string $email, string $password,): Array
    {
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('user');
        $token= $user->createToken('auth-token')->plainTextToken;

        return [
            'user'=>$user,
            'accessToken'=>$token
        ];
    }
}
