<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->has('main_image_path')) {
            $data['main_image'] = $request->main_image_path;
        }

        $product = Product::create($data);

        if ($request->variants) {
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        return redirect()->route('products.index');
    }

    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        $product->variants()->delete();
        if ($request->variants) {
            foreach ($request->variants as $variant) {
                $product->variants()->create($variant);
            }
        }

        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->main_image) {
            Storage::delete('public/' . $product->main_image);
        }

        $product->delete();

        return redirect()->route('products.index');
    }


    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('images', 'public');
            return response()->json(['path' => $path]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

}
