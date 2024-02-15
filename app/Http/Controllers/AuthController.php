<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Requests\LogoutAuthRequest;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterAuthRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            return $this->setResponse(["user"=>$user], 200, "User created successfully");
        } catch (\Exception $e) {
            return $this->setResponse(null, 400, $e->getMessage());
        }
    }

    public function authenticate(LoginAuthRequest $request)
    {
        try {
            if (! $token = JWTAuth::attempt($request->toArray())) {
                return $this->setResponse(null, 400, "Login credentials are invalid");
            }
            return $this->setResponse(["token"=>$token], 200, "Login Successfully");
        } catch (JWTException $e) {
            return $this->setResponse(null, 500, "Could not create token.");
        }
    }

    public function logout(LogoutAuthRequest $request)
    {
        try {
            JWTAuth::setToken($request->token)->invalidate();
            return $this->setResponse(null, 200, "User has been logged out");
        } catch (JWTException $exception) {
            return $this->setResponse(null, 400, "Sorry, user cannot be logged out");
        }
    }

    public function generateToken(Request $request)
    {
        $data = [
            "name"=>$request->username,
            "password"=>$request->password
        ];
        try {
            if (! $token = JWTAuth::attempt($data)) {
                return $this->setResponse(null, 400, "Credentials are invalid");
            }
            return $this->setResponse(["token"=>$token], 200, "Generate token Successfully");
        } catch (JWTException $e) {
            return $this->setResponse(null, 500, "Could not create token.");
        }
    }
}
