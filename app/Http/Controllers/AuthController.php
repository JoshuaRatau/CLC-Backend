<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laravel\Sanctum\Sanctum;



class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname'=> 'required',
            'phoneNum' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'phoneNum' => $request->phoneNum,
                'email' => $request->email,
                'password' => Hash::make($request->password),
               
            ]);

            Auth::login($user);

            return response()->json(['user' => $user, 'message' => 'User registered successfully'], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error'], 500);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Registration error'], 500);
        }

        return response()->json(['message' => 'User registered successfully']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;
            return response()->json(['user' => $user,
            'token' => $token
        ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }





   

   

    


   


    
    

}




