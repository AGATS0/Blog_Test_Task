<?php

namespace App\Actions\Auth;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginAction 
{

    /**
     * login user.
     *
     * 
     * @param  string $email
     * @param  string $password
     * @return array{user:User,accessToken:string}     
     */
    public function execute(string $email, string $password,): Array
    {
        
        $user = User::where([
            'email' => $email,
        ])->first();

        if(!$user || !Hash::check($password, $user->password)){
            throw ValidationException::withMessages([
                'email'=> ['Неверные данные'],
            ]);
        }

        $token= $user->createToken('auth-token')->plainTextToken;

        return [
            'user'=>$user,
            'accessToken'=>$token
        ];
    }
}
