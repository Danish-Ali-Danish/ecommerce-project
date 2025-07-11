<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->take(10)->get();
        $brands = Brand::latest()->take(10)->get();

        $products = Product::orderBy('created_at', 'desc')->take(12)->get();  // Latest 12 products
        // Add this line

        return view('user.home', compact('categories', 'brands', 'products'));
    }
}
