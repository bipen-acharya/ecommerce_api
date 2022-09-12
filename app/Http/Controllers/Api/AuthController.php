<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AuthController extends BaseController
{
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();

            $success['token'] =  $user->createToken('classecom')->plainTextToken;
            $success['name'] =  $user->name;


            return $this->sendResponse($success, 'User Login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Credettials doesnot match']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $password = Hash::make($password);
        $date = date('Y-m-d H:i:s');
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->email_verified_at = $date;
        $user->save();
        // return response()->json($user, 200);
        $success['token'] = $user->createToken('MyAuthApp')->plainTextToken;

        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User created successfully.');
    }
}
