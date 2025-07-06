<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $products = Product::latest()->take(8)->get();  // Optional: featured products

        return view('user.home', compact('categories', 'brands', 'products'));
    }
}
