<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourierController extends Controller
{
    /**
     * Display a listing of the couriers.
     */
    public function index(Request $request): View
    {
        $query = Courier::query();
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        $couriers = $query->latest()->paginate(10);
        
        return view('admin.couriers.index', compact('couriers'));
    }

    /**
     * Show the form for creating a new courier.
     */
    public function create(): View
    {
        return view('admin.couriers.create');
    }

    /**
     * Store a newly created courier in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:couriers',
            'status' => 'required|in:active,inactive',
        ]);

        Courier::create($validated);

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Courier created successfully.');
    }

    /**
     * Display the specified courier.
     */
    public function show(Courier $courier): View
    {
        return view('admin.couriers.show', compact('courier'));
    }

    /**
     * Show the form for editing the specified courier.
     */
    public function edit(Courier $courier): View
    {
        return view('admin.couriers.edit', compact('courier'));
    }

    /**
     * Update the specified courier in storage.
     */
    public function update(Request $request, Courier $courier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:couriers,name,' . $courier->id,
            'status' => 'required|in:active,inactive',
        ]);

        $courier->update($validated);

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Courier updated successfully.');
    }

    /**
     * Remove the specified courier from storage.
     */
    public function destroy(Courier $courier): RedirectResponse
    {
        // Check if courier is used by any order
        $ordersCount = $courier->orders()->count();
        
        if ($ordersCount > 0) {
            return redirect()->route('admin.couriers.index')
                ->with('error', 'Cannot delete courier as it is associated with orders.');
        }
        
        $courier->delete();

        return redirect()->route('admin.couriers.index')
            ->with('success', 'Courier deleted successfully.');
    }
}