<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
}
