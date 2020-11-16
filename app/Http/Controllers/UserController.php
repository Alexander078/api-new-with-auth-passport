<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
   public function store(Request $request){

       $input = $request->all();
       $input['password'] = Hash::make($request->password);
       //$input['password'] = bcrypt($request->password);

       User::create($input);

       return response()->json([
            'res' => true,
            'message' => 'Usuario creado correctamente'
       ], 200);
   }

   public function login(Request $request){
       $user = User::whereEmail($request->email)->first();

       if (!is_null($user) && Hash::check($request->password, $user->password)){
         
             $token = $user->createToken('api-new-with-auth-passport')->accessToken;

              $user->save();
        
               return response()->json([
                 'res' => true,
                 'token' => $token,
                'message' => 'Bienvenido al sistema'
               ], 200);
          }

        else {

            return response()->json([
                'res' => false,
                'message' => 'Cuenta o password incorrectos'
              ], 200);
        }
    
   } 
   
   public function logout() {

     $user = auth()->user();
     $user->tokens->each(function($token, $key){
        $token->delete();
     });
     $user->save();

     return response()->json([
        'res' => true,
        'message' => 'Adios'
      ], 200);

   }



}
