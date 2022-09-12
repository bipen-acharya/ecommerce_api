<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Cart as CartResource;
use App\Http\Resources\UserCart as UserCartResource;
use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\User;

class CartController extends BaseController
{
    //
    public function getCarts()
    {
        $cart = Cart::all();
        return $this->sendResponse(CartResource::collection($cart), 'cart Fetched');
    }

    public function getSingleCarts($id)
    {
    }

    public function getCartDelete($id)
    {
        $cart = Cart::find($id);
        if (is_null($cart)) {
            return $this->sendError('product does not exist.');
        }
        $cart->delete();
        return $this->sendResponse([], 'cart deleted.');
    }

    public function postAddCart(Request $request,)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }
        $product = Product::all();
        $user_id = $request->input('user_id');

        $product_id = $request->input('product_id');
        $product = Product::find($product_id);

        $quantity = $request->input('quantity');
        $amount = $product->product_cost;
        $total_amount = $quantity * $amount;
        $cart_code = Str::random(5);
        return response()->json($cart_code, 200);
        $cart = new Cart();
        $cart->user_id = $user_id;
        $cart->product_id = $product_id;
        $cart->quantity = $quantity;
        $cart->amount = $amount;
        $cart->total_amount = $total_amount;
        $cart->cart_code = $cart_code;

        $cart->save();
        return $this->sendResponse(new CartResource($cart), 'cart added');
    }

    public function postEditCart($id, Request $request)
    {
    }

    public function getUserWithCarts($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('user does not exist.');
        }
        // return CategoryProductResource::collection($category);
        return $this->sendResponse(new UserCartResource($user), 'Single user fetched.');
    }
}
