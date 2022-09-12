<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

class UserController extends BaseController
{
    public function getUser(Request $request)
    {
        $user = User::all();
        return $this->sendResponse(UserResource::collection($user), 'user Fetched');
    }
}
