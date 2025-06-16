<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DiscountController extends Controller
{
    /**
     * Display a listing of the discounts.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $query = Discount::where('organization_id', $organization->id)->with('project');
        
        // Filter by project
        if ($request->has('project_id') && !empty($request->project_id)) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Search by name or code
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        $discounts = $query->latest()->paginate(10);
        
        $projects = Project::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        return view('organization.discounts.index', compact('discounts', 'projects'));
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create(): View
    {
        $organization = Auth::user()->organization;
        
        $projects = Project::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('organization.discounts.create', compact('projects'));
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'project_id' => 'nullable|exists:projects,id',
        ]);
        
        // Validate that the project belongs to this organization if provided
        if (!empty($validated['project_id'])) {
            $project = Project::where('id', $validated['project_id'])
                ->where('organization_id', $organization->id)
                ->firstOrFail();
        }
        
        $discount = new Discount($validated);
        $discount->organization_id = $organization->id;
        $discount->save();
        
        return redirect()->route('organization.discounts.index')
            ->with('success', 'Discount created successfully.');
    }

    /**
     * Display the specified discount.
     */
    public function show(Discount $discount): View
    {
        $this->checkDiscountOwnership($discount);
        
        $discount->load('project', 'organization');
        
        return view('organization.discounts.show', compact('discount'));
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount): View
    {
        $this->checkDiscountOwnership($discount);
        
        $organization = Auth::user()->organization;
        
        $projects = Project::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('organization.discounts.edit', compact('discount', 'projects'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $this->checkDiscountOwnership($discount);
        
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts,code,' . $discount->id,
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'project_id' => 'nullable|exists:projects,id',
        ]);
        
        // Validate that the project belongs to this organization if provided
        if (!empty($validated['project_id'])) {
            $project = Project::where('id', $validated['project_id'])
                ->where('organization_id', $organization->id)
                ->firstOrFail();
        }
        
        $discount->update($validated);
        
        return redirect()->route('organization.discounts.index')
            ->with('success', 'Discount updated successfully.');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(Discount $discount): RedirectResponse
    {
        $this->checkDiscountOwnership($discount);
        
        // Check if discount is used in orders (if we had a discount_id field in orders)
        // For now, allow deletion
        
        $discount->delete();
        
        return redirect()->route('organization.discounts.index')
            ->with('success', 'Discount deleted successfully.');
    }

    /**
     * Check if the discount belongs to the current organization.
     */
    protected function checkDiscountOwnership(Discount $discount): void
    {
        $organization = Auth::user()->organization;
        
        if ($discount->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to discount.');
        }
    }
}