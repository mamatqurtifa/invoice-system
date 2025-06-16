<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Tax;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TaxController extends Controller
{
    /**
     * Display a listing of the taxes.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $query = Tax::where('organization_id', $organization->id)->with('project');
        
        // Filter by project
        if ($request->has('project_id') && !empty($request->project_id)) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by is_default
        if ($request->has('is_default') && $request->is_default !== null) {
            $query->where('is_default', $request->is_default);
        }
        
        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        $taxes = $query->latest()->paginate(10);
        
        $projects = Project::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        return view('organization.taxes.index', compact('taxes', 'projects'));
    }

    /**
     * Show the form for creating a new tax.
     */
    public function create(): View
    {
        $organization = Auth::user()->organization;
        
        $projects = Project::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('organization.taxes.create', compact('projects'));
    }

    /**
     * Store a newly created tax in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'is_default' => 'boolean',
            'project_id' => 'nullable|exists:projects,id',
        ]);
        
        // Validate that the project belongs to this organization if provided
        if (!empty($validated['project_id'])) {
            $project = Project::where('id', $validated['project_id'])
                ->where('organization_id', $organization->id)
                ->firstOrFail();
        }
        
        DB::beginTransaction();
        
        try {
            // If this tax is set as default, unset any existing default for the same scope
            if ($validated['is_default']) {
                if (empty($validated['project_id'])) {
                    // Organization level default
                    Tax::where('organization_id', $organization->id)
                        ->whereNull('project_id')
                        ->update(['is_default' => false]);
                } else {
                    // Project level default
                    Tax::where('organization_id', $organization->id)
                        ->where('project_id', $validated['project_id'])
                        ->update(['is_default' => false]);
                }
            }
            
            $tax = new Tax($validated);
            $tax->organization_id = $organization->id;
            $tax->save();
            
            DB::commit();
            
            return redirect()->route('organization.taxes.index')
                ->with('success', 'Tax created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to create tax: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified tax.
     */
    public function show(Tax $tax): View
    {
        $this->checkTaxOwnership($tax);
        
        $tax->load('project', 'organization');
        
        return view('organization.taxes.show', compact('tax'));
    }

    /**
     * Show the form for editing the specified tax.
     */
    public function edit(Tax $tax): View
    {
        $this->checkTaxOwnership($tax);
        
        $organization = Auth::user()->organization;
        
        $projects = Project::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('organization.taxes.edit', compact('tax', 'projects'));
    }

    /**
     * Update the specified tax in storage.
     */
    public function update(Request $request, Tax $tax): RedirectResponse
    {
        $this->checkTaxOwnership($tax);
        
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'is_default' => 'boolean',
            'project_id' => 'nullable|exists:projects,id',
        ]);
        
        // Validate that the project belongs to this organization if provided
        if (!empty($validated['project_id'])) {
            $project = Project::where('id', $validated['project_id'])
                ->where('organization_id', $organization->id)
                ->firstOrFail();
        }
        
        DB::beginTransaction();
        
        try {
            // If this tax is set as default, unset any existing default for the same scope
            if ($validated['is_default']) {
                if (empty($validated['project_id'])) {
                    // Organization level default
                    Tax::where('organization_id', $organization->id)
                        ->whereNull('project_id')
                        ->where('id', '!=', $tax->id) // Don't unset this tax
                        ->update(['is_default' => false]);
                } else {
                    // Project level default
                    Tax::where('organization_id', $organization->id)
                        ->where('project_id', $validated['project_id'])
                        ->where('id', '!=', $tax->id) // Don't unset this tax
                        ->update(['is_default' => false]);
                }
            }
            
            $tax->update($validated);
            
            DB::commit();
            
            return redirect()->route('organization.taxes.index')
                ->with('success', 'Tax updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update tax: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified tax from storage.
     */
    public function destroy(Tax $tax): RedirectResponse
    {
        $this->checkTaxOwnership($tax);
        
        // Check if tax is used in orders (not directly possible with current DB structure)
        // For now, allow deletion
        
        $tax->delete();
        
        return redirect()->route('organization.taxes.index')
            ->with('success', 'Tax deleted successfully.');
    }

    /**
     * Check if the tax belongs to the current organization.
     */
    protected function checkTaxOwnership(Tax $tax): void
    {
        $organization = Auth::user()->organization;
        
        if ($tax->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to tax.');
        }
    }
}