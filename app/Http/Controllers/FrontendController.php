<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;  // Add at the top with Category
use App\Models\Category;

class FrontendController extends Controller
{
    public function allCate()
    {
        $categories = Category::all();
        return view('user.categories.index', compact('categories'));
    }

    public function preview($id)
    {
        $category = Category::findOrFail($id);

        $imagePath = $category->file_path
            ? asset('storage/' . $category->file_path)
            : asset('images/default-category.png');

        return response()->json([
            'image' => $imagePath
        ]);
    }

    public function allBrands()
    {
        $brands = Brand::all();
        return view('user.brands.index', compact('brands'));
    }

    public function previewBrand($id)
    {
        $brand = Brand::findOrFail($id);

        $imagePath = $brand->file_path
            ? asset('storage/' . $brand->file_path)
            : asset('images/default-brand.png');

        return response()->json([
            'image' => $imagePath
        ]);
    }
}
