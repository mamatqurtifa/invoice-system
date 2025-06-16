<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $query = Product::where('organization_id', $organization->id);
        
        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by price range
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }
        
        $products = $query->latest()->paginate(12);
        
        return view('organization.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('organization.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $product = new Product($validated);
        $product->organization_id = $organization->id;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product-images', 'public');
            $product->image = $path;
        }
        
        $product->save();
        
        return redirect()->route('organization.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        $this->checkProductOwnership($product);
        
        $product->load('projectProducts.project');
        
        return view('organization.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $this->checkProductOwnership($product);
        
        return view('organization.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->checkProductOwnership($product);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $product->update($validated);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product-images', 'public');
            $product->update(['image' => $path]);
        }
        
        return redirect()->route('organization.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->checkProductOwnership($product);
        
        // Check if product is used in projects
        if ($product->projectProducts()->exists()) {
            return redirect()->route('organization.products.index')
                ->with('error', 'Cannot delete product as it is used in projects.');
        }
        
        $product->delete();
        
        return redirect()->route('organization.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Check if the product belongs to the current organization.
     */
    protected function checkProductOwnership(Product $product): void
    {
        $organization = Auth::user()->organization;
        
        if ($product->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to product.');
        }
    }
}