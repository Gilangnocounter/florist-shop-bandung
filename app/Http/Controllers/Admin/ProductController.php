<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $imagePath = $request->file('image')->store('products', 'public');

            Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'image' => $imagePath,
            'description' => $request->name,
            'stock' => $request->stock ?? 0, 
        ]);

            return redirect()->route('admin.products')->with('success', 'Bunga berhasil ditambahkan!');
        }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

   public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0', // Validasi stok tambahan
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'stock' => $request->stock, // Simpan stok baru
        ]);

        return redirect()->route('admin.products')->with('success', 'Data bunga diperbarui!');
    }

    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Bunga telah dihapus.');
    }
}