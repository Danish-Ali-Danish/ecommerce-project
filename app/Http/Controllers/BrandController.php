<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'newest');
        $search = $request->get('search');

        $query = Brand::with('category');

        // 🔍 Apply search if provided
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // ⬇️ Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'az':
                $query->orderBy('name', 'asc');
                break;
            case 'za':
                $query->orderBy('name', 'desc');
                break;
            default:  // newest
                $query->orderBy('created_at', 'desc');
        }

        // 🔁 For AJAX request return JSON
        if ($request->ajax()) {
            return response()->json($query->get());
        }

        // 🖥️ Normal page load
        $brands = $query->get();
        $categories = Category::all();

        return view('admin.brands.index', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads', 'public');
        }

        $brand = Brand::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => 'Brand added successfully!', 'brand' => $brand]);
    }

    public function show($id)
    {
        $brand = Brand::with('category')->findOrFail($id);
        return response()->json($brand);
    }

    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('file')) {
            if ($brand->file_path && Storage::disk('public')->exists($brand->file_path)) {
                Storage::disk('public')->delete($brand->file_path);
            }

            $filePath = $request->file('file')->store('uploads', 'public');
            $brand->file_path = $filePath;
        }

        $brand->name = $request->name;
        $brand->category_id = $request->category_id;
        $brand->save();

        return response()->json(['message' => 'Brand updated successfully!', 'brand' => $brand]);
    }

    public function destroy(Brand $brand)
    {
        try {
            if ($brand->file_path && Storage::disk('public')->exists($brand->file_path)) {
                Storage::disk('public')->delete($brand->file_path);
            }

            $brand->delete();
            return response()->json(['message' => 'Brand deleted successfully!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Cannot delete brand. It is associated with other records.'
            ], 409);
        }
    }
}
