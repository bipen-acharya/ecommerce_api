<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\Order as OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrderController extends BaseController
{
    //
    public function getOrder()
    {
    }
    public function addOrder(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'user_id' => 'required',
        //     'payement_status' => 'required',

        // ]);
        // if ($validator->fails()) {
        //     return $this->sendError('Error Validation', $validator->errors(), 400);
        // }
        $user_id = $request->input('user_id');
        $payement_status = $request->input('payement_status');

        $user = User::find($user_id);
        $user_name = $user->name;



        $cart = Cart::where('user_id', $user_id)->get();
        $cart_code = Cart::where('user_id', $user_id)->get('cart_code');
        // return response()->json($payement_status, 200);


        $amount = Cart::where('user_id', $user->id)->sum('total_amount');
        // return response()->json($total_amount, 200);
        // $amount = $cart->total_amount;
        $order = new Order();
        $order->user_id = $user_id;
        $order->payement_status = $payement_status;
        $order->user_name = $user_name;
        // $order->user_email = $user_email;
        $order->cart_code = $cart_code;
        $order->total_amount = $amount;
        $order->save();
        return $this->sendResponse(new OrderResource($order), 'order added');
    }
}
