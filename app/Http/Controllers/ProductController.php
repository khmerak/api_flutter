<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with([
            'category:id,name'
        ])->get([
            'id',
            'title',
            'description',
            'price',
            'image',
            'stock',
            'category_id'
        ]);

        return response()->json($products, 200);
    }

    public function products(){
        $products = Product::take(10)->get();
        return response()->json($products, 200);
    }

    public function productPage()
    {
        return view('product');
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'stock' => 'nullable|integer|min:0',
            'price'        => 'required|numeric',
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'price', 'category_id']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title'         => 'sometimes|required|string|max:255',
            'description'  => 'sometimes|nullable|string',
            'price'        => 'sometimes|required|numeric',
            'stock'        => 'sometimes|nullable|integer|min:0',
            'category_id'  => 'sometimes|required|exists:categories,id',
            'image'        => 'sometimes|image|max:2048',
        ]);

        // Update only sent fields
        if ($request->has('name')) {
            $product->name = $request->name;
        }
        if ($request->has('description')) {
            $product->description = $request->description;
        }
        if ($request->has('price')) {
            $product->price = $request->price;
        }
        if ($request->has('category_id')) {
            $product->category_id = $request->category_id;
        }

        // Update image (delete old & upload new)
        if ($request->hasFile('image')) {

            // 🔥 Delete old image from storage
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Upload new image
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return response()->json($product, 200);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete product image
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete product
        $product->delete();

        return response()->json(null, 204);
    }
}
