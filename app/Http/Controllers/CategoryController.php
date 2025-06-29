<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $categories;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(Category::all());
        }
        return view('admin.categories.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'file' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'file_path' => $filePath
        ]);

        return response()->json(['message' => 'Category added successfully!', 'category' => $category]);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Delete old file if new file uploaded
        if ($request->hasFile('file')) {
            if ($category->file_path && \Storage::disk('public')->exists($category->file_path)) {
                \Storage::disk('public')->delete($category->file_path);
            }

            $filePath = $request->file('file')->store('uploads', 'public');
            $category->file_path = $filePath;
        }

        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Category updated successfully!', 'category' => $category]);
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->file_path && \Storage::disk('public')->exists($category->file_path)) {
                \Storage::disk('public')->delete($category->file_path);
            }

            $category->delete();
            return response()->json(['message' => 'Category deleted successfully!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Cannot delete category. It is associated with other records.'
            ], 409);
        }
    }
}
