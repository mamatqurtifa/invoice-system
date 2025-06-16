<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $query = Project::where('organization_id', $organization->id);
        
        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        $projects = $query->latest()->paginate(10);
        
        return view('organization.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        return view('organization.projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:preorder,direct_order',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,cancelled',
        ]);
        
        $project = new Project($validated);
        $project->organization_id = $organization->id;
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('project-logos', 'public');
            $project->logo = $path;
        }
        
        $project->save();
        
        return redirect()->route('organization.projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): View
    {
        $this->checkProjectOwnership($project);
        
        $project->load(['projectProducts.product']);
        
        return view('organization.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project): View
    {
        $this->checkProjectOwnership($project);
        
        return view('organization.projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->checkProjectOwnership($project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:preorder,direct_order',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,cancelled',
        ]);
        
        $project->update($validated);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('project-logos', 'public');
            $project->update(['logo' => $path]);
        }
        
        return redirect()->route('organization.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->checkProjectOwnership($project);
        
        // Check if project has orders
        if ($project->orders()->exists()) {
            return redirect()->route('organization.projects.index')
                ->with('error', 'Cannot delete project as it has associated orders.');
        }
        
        $project->delete();
        
        return redirect()->route('organization.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Add products to the project.
     */
    public function addProducts(Request $request, Project $project): View
    {
        $this->checkProjectOwnership($project);
        
        $organization = Auth::user()->organization;
        
        // Get all products of the organization
        $products = Product::where('organization_id', $organization->id)->get();
        
        // Get already assigned product IDs
        $assignedProductIds = $project->projectProducts()->pluck('product_id')->toArray();
        
        return view('organization.projects.add-products', compact('project', 'products', 'assignedProductIds'));
    }

    /**
     * Store products to the project.
     */
    public function storeProducts(Request $request, Project $project): RedirectResponse
    {
        $this->checkProjectOwnership($project);
        
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock' => 'nullable|integer|min:0',
        ]);
        
        foreach ($validated['products'] as $productData) {
            // Check if the product already exists in the project
            $projectProduct = ProjectProduct::where('project_id', $project->id)
                ->where('product_id', $productData['product_id'])
                ->first();
                
            if ($projectProduct) {
                // Update existing project product
                $projectProduct->price = $productData['price'];
                $projectProduct->stock = $productData['stock'] ?? null;
                $projectProduct->stock_status = $productData['stock'] > 0 ? 'available' : 'out_of_stock';
                $projectProduct->save();
            } else {
                // Create new project product
                ProjectProduct::create([
                    'project_id' => $project->id,
                    'product_id' => $productData['product_id'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'] ?? null,
                    'stock_status' => $productData['stock'] > 0 ? 'available' : 'out_of_stock',
                ]);
            }
        }
        
        return redirect()->route('organization.projects.show', $project)
            ->with('success', 'Products added to project successfully.');
    }

    /**
     * Remove a product from the project.
     */
    public function removeProduct(Project $project, ProjectProduct $projectProduct): RedirectResponse
    {
        $this->checkProjectOwnership($project);
        
        // Ensure the project product belongs to this project
        if ($projectProduct->project_id !== $project->id) {
            abort(403);
        }
        
        // Check if the project product is used in orders
        if ($projectProduct->orderItems()->exists()) {
            return redirect()->route('organization.projects.show', $project)
                ->with('error', 'Cannot remove product as it is used in orders.');
        }
        
        $projectProduct->delete();
        
        return redirect()->route('organization.projects.show', $project)
            ->with('success', 'Product removed from project successfully.');
    }

    /**
     * Check if the project belongs to the current organization.
     */
    protected function checkProjectOwnership(Project $project): void
    {
        $organization = Auth::user()->organization;
        
        if ($project->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to project.');
        }
    }
}