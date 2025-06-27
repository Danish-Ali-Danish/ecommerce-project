<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function home()
    {
        return view('user.home');
    }

    public function products()
    {
        return view('user.products');
    }

    public function productDetails($id)
    {
        return view('user.product-details', ['id' => $id]);
    }

    public function cart()
    {
        return view('user.cart');
    }

    public function checkout()
    {
        return view('user.checkout');
    }

    public function orders()
    {
        return view('user.orders');
    }

    public function wishlist()
    {
        return view('user.wishlist');
    }
}
