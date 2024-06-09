<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            $imagePath = "";
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images', $imageName);
                $imagePath = Storage::url($imagePath);
            }
            Product::create([
                "title"=> $request->title,
                "description"=> $request->description,
                "price"=> $request->price,
                "image"=> $imagePath
            ]);
            return response()->json(['message' => 'Product created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        try {
            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                if ($imagePath && Storage::exists(str_replace('/storage', 'public', $imagePath))) {
                    Storage::delete(str_replace('/storage', 'public', $imagePath));
                }
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/images', $imageName);
                $imagePath = Storage::url($imagePath);
            }
            $product->update([
                "title" => $request->title,
                "description" => $request->description,
                "price"=> $request->price,
                "image"=> $imagePath
            ]);
            return response()->json(['message' => 'Product updated successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        Product::find($id)->delete();
        return response()->json(['message' => 'Product deleted successfully'], 201);
    }
}
