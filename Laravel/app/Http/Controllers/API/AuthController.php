<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Routing\Middleware;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(`auth:api`)->except('login');
    }
    public function login($request){
        $credentials = $request->only(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function user(){
        return response()->json(auth('api')->user());

    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'Successfully logged out']);
    }
    public function refresh(){

        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Config::get('jwt.ttl') * 60
        ]);
    }
}
