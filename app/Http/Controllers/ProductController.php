<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Inventory;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by name or description
        if ($request->has('search') && $request->search !== null) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by stock status
        if ($request->has('filter') && $request->filter !== null) {
            if ($request->filter === 'low_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->where('quantity', '<', 10);
                });
            } elseif ($request->filter === 'out_of_stock') {
                $query->whereHas('inventory', function ($q) {
                    $q->where('quantity', '=', 0);
                });
            }
        }

        // Sort by selected option
        if ($request->has('sort') && $request->sort !== null) {
            if ($request->sort === 'low_to_high') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'high_to_low') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        if ($request->ajax()) {
            return view('products.partials.products-grid', compact('products'))->render();
        }

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Exact directory: public/img
            $destinationPath = base_path('public/img');

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the uploaded file to the destination
            $image->move($destinationPath, $imageName);

            // Store the relative path to save in the database
            $imagePath = 'img/' . $imageName;
        }

        // Save product details in the database
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->img_path = $imagePath;
        $product->save();

        // Create corresponding inventory record with a default quantity
        $inventory = new Inventory();
        $inventory->product_id = $product->id;
        $inventory->quantity = 0;
        $inventory->save();

        // Redirect with success message
        return redirect()->route('products.index')->with('success', 'Product and inventory created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the request inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        // Update product details
        $product->name = $request->name;
        $product->price = $request->price;

        // Handle image upload
        if ($request->hasFile('img_path')) {
            // Delete old image if it exists
            if ($product->img_path && file_exists(public_path($product->img_path))) {
                unlink(public_path($product->img_path));
            }

            // Upload new image
            $image = $request->file('img_path');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = base_path('public/img');

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $imageName);
            $product->img_path = 'img/' . $imageName;
        }

        // Save updated product
        $product->save();

        // Redirect with success message
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->img_path && file_exists(public_path($product->img_path))) {
            unlink(public_path($product->img_path));
        }

        // Delete the product from the database
        $product->delete();

        // Redirect with success message
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
