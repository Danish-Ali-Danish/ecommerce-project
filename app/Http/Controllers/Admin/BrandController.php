<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    // Return view or JSON list of all brands
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brands = Brand::with('category')->get();
            return response()->json($brands);
        }

        $brands = Brand::with('category')->get();
        $categories = Category::all();
        return view('brands.index', compact('brands', 'categories'));
    }

    // Store new brand
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $brand = Brand::create($request->only('name', 'category_id'));

        return response()->json(['message' => 'Brand added successfully!', 'brand' => $brand]);
    }

    // Show single brand for editing
    public function show($id)
    {
        $brand = Brand::with('category')->findOrFail($id);
        return response()->json($brand);
    }

    // Update brand
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $brand->update($request->only('name', 'category_id'));

        return response()->json(['message' => 'Brand updated successfully!', 'brand' => $brand]);
    }

    // Delete brand
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return response()->json(['message' => 'Brand deleted successfully!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Cannot delete brand. It is associated with other records.'
            ], 409);
        }
    }
}
