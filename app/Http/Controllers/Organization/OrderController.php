<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\ProjectProduct;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected $invoiceService;

    /**
     * Create a new controller instance.
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the orders.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        // Get projects for this organization
        $projects = Project::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        $query = Order::whereHas('project', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        })->with(['customer', 'project']);
        
        // Filter by project
        if ($request->has('project_id') && !empty($request->project_id)) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && !empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by payment type
        if ($request->has('payment_type') && !empty($request->payment_type)) {
            $query->where('payment_type', $request->payment_type);
        }
        
        // Filter by date range
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->where('order_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->where('order_date', '<=', $request->end_date);
        }
        
        // Search by order number or customer name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->latest()->paginate(15);
        
        return view('organization.orders.index', compact('orders', 'projects'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(): View
    {
        $organization = Auth::user()->organization;
        
        $projects = Project::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        $customers = Customer::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        $paymentMethods = PaymentMethod::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $couriers = \App\Models\Courier::where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('organization.orders.create', compact('projects', 'customers', 'paymentMethods', 'couriers'));
    }

    /**
     * Get project products for AJAX request.
     */
    public function getProjectProducts(Request $request)
    {
        $organization = Auth::user()->organization;
        
        $projectId = $request->input('project_id');
        
        // Validate that the project belongs to this organization
        $project = Project::where('id', $projectId)
            ->where('organization_id', $organization->id)
            ->firstOrFail();
            
        $projectProducts = ProjectProduct::with('product')
            ->where('project_id', $projectId)
            ->get();
            
        return response()->json($projectProducts);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'customer_id' => 'required|exists:customers,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'order_date' => 'required|date',
            'payment_type' => 'required|in:down_payment,full_payment',
            'shipping_method' => 'required|in:self_pickup,courier',
            'courier_id' => 'nullable|required_if:shipping_method,courier|exists:couriers,id',
            'tracking_number' => 'nullable|string|max:50',
            'shipping_cost' => 'nullable|numeric|min:0',
            'down_payment_amount' => 'nullable|required_if:payment_type,down_payment|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.project_product_id' => 'required|exists:project_products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);
        
        // Validate that the project belongs to this organization
        $project = Project::where('id', $validated['project_id'])
            ->where('organization_id', $organization->id)
            ->firstOrFail();
            
        // Validate that the customer belongs to this organization
        $customer = Customer::where('id', $validated['customer_id'])
            ->where('organization_id', $organization->id)
            ->firstOrFail();
            
        // Validate that the payment method belongs to this organization
        $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
            ->where('organization_id', $organization->id)
            ->firstOrFail();
            
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Generate unique order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            // Calculate subtotal
            $subtotal = 0;
            $taxAmount = 0;
            
            foreach ($validated['items'] as $item) {
                $projectProduct = ProjectProduct::findOrFail($item['project_product_id']);
                $itemTotal = $projectProduct->price * $item['quantity'];
                $itemDiscount = $item['discount'] ?? 0;
                $subtotal += ($itemTotal - $itemDiscount);
            }
            
            // Apply tax if project has tax
            $tax = $project->taxes()->where('is_default', true)->first();
            if ($tax) {
                $taxAmount = ($subtotal * $tax->percentage) / 100;
            }
            
            // Create order
            $order = new Order([
                'project_id' => $validated['project_id'],
                'customer_id' => $validated['customer_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'order_number' => $orderNumber,
                'order_date' => $validated['order_date'],
                'payment_type' => $validated['payment_type'],
                'payment_status' => 'pending',
                'shipping_method' => $validated['shipping_method'],
                'courier_id' => $validated['courier_id'] ?? null,
                'tracking_number' => $validated['tracking_number'] ?? null,
                'subtotal' => $subtotal,
                'discount' => 0, // No order-level discount for now
                'tax_amount' => $taxAmount,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'total_amount' => $subtotal + $taxAmount + ($validated['shipping_cost'] ?? 0),
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Handle down payment
            if ($validated['payment_type'] === 'down_payment') {
                $order->down_payment_amount = $validated['down_payment_amount'];
                $order->remaining_payment = $order->total_amount - $validated['down_payment_amount'];
            }
            
            $order->save();
            
            // Create order items
            foreach ($validated['items'] as $item) {
                $projectProduct = ProjectProduct::findOrFail($item['project_product_id']);
                $quantity = $item['quantity'];
                $unitPrice = $projectProduct->price;
                $discount = $item['discount'] ?? 0;
                $itemTotal = ($unitPrice * $quantity) - $discount;
                
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'project_product_id' => $projectProduct->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'tax' => 0, // No item-level tax for now
                    'total' => $itemTotal,
                ]);
                
                $orderItem->save();
                
                // Update stock for direct_order projects
                if ($project->type === 'direct_order' && $projectProduct->stock !== null) {
                    $projectProduct->stock -= $quantity;
                    if ($projectProduct->stock <= 0) {
                        $projectProduct->stock_status = 'out_of_stock';
                    } elseif ($projectProduct->stock <= 5) {
                        $projectProduct->stock_status = 'limited';
                    }
                    $projectProduct->save();
                }
            }
            
            // Generate invoice
            $this->invoiceService->generateInvoice($order);
            
            DB::commit();
            
            return redirect()->route('organization.orders.show', $order)
                ->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to create order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $this->checkOrderOwnership($order);
        
        $order->load([
            'customer',
            'project',
            'project.organization',
            'orderItems.projectProduct.product',
            'paymentMethod',
            'courier',
            'invoice'
        ]);
        
        return view('organization.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order): View|RedirectResponse
    {
        $this->checkOrderOwnership($order);
        
        // Only pending orders can be edited
        if ($order->payment_status !== 'pending') {
            return redirect()->route('organization.orders.show', $order)
                ->with('error', 'Only pending orders can be edited.');
        }
        
        $organization = Auth::user()->organization;
        
        $order->load([
            'customer',
            'project',
            'orderItems.projectProduct.product',
            'paymentMethod',
            'courier'
        ]);
        
        $customers = Customer::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        $paymentMethods = PaymentMethod::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $couriers = \App\Models\Courier::where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('organization.orders.edit', compact('order', 'customers', 'paymentMethods', 'couriers'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $this->checkOrderOwnership($order);
        
        // Only pending orders can be updated
        if ($order->payment_status !== 'pending') {
            return redirect()->route('organization.orders.show', $order)
                ->with('error', 'Only pending orders can be updated.');
        }
        
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'order_date' => 'required|date',
            'payment_type' => 'required|in:down_payment,full_payment',
            'shipping_method' => 'required|in:self_pickup,courier',
            'courier_id' => 'nullable|required_if:shipping_method,courier|exists:couriers,id',
            'tracking_number' => 'nullable|string|max:50',
            'shipping_cost' => 'nullable|numeric|min:0',
            'down_payment_amount' => 'nullable|required_if:payment_type,down_payment|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        $organization = Auth::user()->organization;
        
        // Validate that the customer belongs to this organization
        $customer = Customer::where('id', $validated['customer_id'])
            ->where('organization_id', $organization->id)
            ->firstOrFail();
            
        // Validate that the payment method belongs to this organization
        $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
            ->where('organization_id', $organization->id)
            ->firstOrFail();
            
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Update order
            $order->customer_id = $validated['customer_id'];
            $order->payment_method_id = $validated['payment_method_id'];
            $order->order_date = $validated['order_date'];
            $order->payment_type = $validated['payment_type'];
            $order->shipping_method = $validated['shipping_method'];
            $order->courier_id = $validated['courier_id'] ?? null;
            $order->tracking_number = $validated['tracking_number'] ?? null;
            $order->shipping_cost = $validated['shipping_cost'] ?? 0;
            $order->notes = $validated['notes'] ?? null;
            
            // Recalculate total with new shipping cost
            $order->total_amount = $order->subtotal + $order->tax_amount + ($validated['shipping_cost'] ?? 0);
            
            // Handle down payment
            if ($validated['payment_type'] === 'down_payment') {
                $order->down_payment_amount = $validated['down_payment_amount'];
                $order->remaining_payment = $order->total_amount - $validated['down_payment_amount'];
            } else {
                $order->down_payment_amount = null;
                $order->remaining_payment = null;
            }
            
            $order->save();
            
            // Update invoice if exists
            if ($order->invoice) {
                $this->invoiceService->updateInvoice($order->invoice);
            }
            
            DB::commit();
            
            return redirect()->route('organization.orders.show', $order)
                ->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update order items.
     */
    public function updateItems(Request $request, Order $order): RedirectResponse
    {
        $this->checkOrderOwnership($order);
        
        // Only pending orders can be updated
        if ($order->payment_status !== 'pending') {
            return redirect()->route('organization.orders.show', $order)
                ->with('error', 'Only pending orders can have their items updated.');
        }
        
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:order_items,id',
            'items.*.project_product_id' => 'required|exists:project_products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            $project = $order->project;
            $subtotal = 0;
            
            // Track old and new item IDs to determine which ones to delete
            $oldItemIds = $order->orderItems()->pluck('id')->toArray();
            $newItemIds = [];
            
            foreach ($validated['items'] as $itemData) {
                $projectProduct = ProjectProduct::findOrFail($itemData['project_product_id']);
                $quantity = $itemData['quantity'];
                $unitPrice = $projectProduct->price;
                $discount = $itemData['discount'] ?? 0;
                $itemTotal = ($unitPrice * $quantity) - $discount;
                
                // Add to subtotal
                $subtotal += $itemTotal;
                
                if (isset($itemData['id'])) {
                    // Update existing item
                    $orderItem = OrderItem::findOrFail($itemData['id']);
                    $newItemIds[] = $orderItem->id;
                    
                    // Restore stock for direct_order projects if quantity decreased
                    if ($project->type === 'direct_order' && $projectProduct->stock !== null && $orderItem->quantity > $quantity) {
                        $stockDiff = $orderItem->quantity - $quantity;
                        $projectProduct->stock += $stockDiff;
                        if ($projectProduct->stock > 0) {
                            $projectProduct->stock_status = $projectProduct->stock <= 5 ? 'limited' : 'available';
                        }
                        $projectProduct->save();
                    }
                    // Reduce stock if quantity increased
                    elseif ($project->type === 'direct_order' && $projectProduct->stock !== null && $orderItem->quantity < $quantity) {
                        $stockDiff = $quantity - $orderItem->quantity;
                        $projectProduct->stock -= $stockDiff;
                        if ($projectProduct->stock <= 0) {
                            $projectProduct->stock_status = 'out_of_stock';
                        } elseif ($projectProduct->stock <= 5) {
                            $projectProduct->stock_status = 'limited';
                        }
                        $projectProduct->save();
                    }
                    
                    // Update the item
                    $orderItem->update([
                        'project_product_id' => $projectProduct->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount' => $discount,
                        'total' => $itemTotal,
                    ]);
                } else {
                    // Create new item
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'project_product_id' => $projectProduct->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount' => $discount,
                        'tax' => 0,
                        'total' => $itemTotal,
                    ]);
                    
                    $newItemIds[] = $orderItem->id;
                    
                    // Update stock for direct_order projects
                    if ($project->type === 'direct_order' && $projectProduct->stock !== null) {
                        $projectProduct->stock -= $quantity;
                        if ($projectProduct->stock <= 0) {
                            $projectProduct->stock_status = 'out_of_stock';
                        } elseif ($projectProduct->stock <= 5) {
                            $projectProduct->stock_status = 'limited';
                        }
                        $projectProduct->save();
                    }
                }
            }
            
            // Delete items that were removed
            $itemsToDelete = array_diff($oldItemIds, $newItemIds);
            if (!empty($itemsToDelete)) {
                // Restore stock for deleted items
                foreach ($itemsToDelete as $itemId) {
                    $item = OrderItem::find($itemId);
                    if ($item && $project->type === 'direct_order') {
                        $projectProduct = $item->projectProduct;
                        if ($projectProduct && $projectProduct->stock !== null) {
                            $projectProduct->stock += $item->quantity;
                            if ($projectProduct->stock > 0) {
                                $projectProduct->stock_status = $projectProduct->stock <= 5 ? 'limited' : 'available';
                            }
                            $projectProduct->save();
                        }
                    }
                }
                
                OrderItem::whereIn('id', $itemsToDelete)->delete();
            }
            
            // Calculate tax
            $taxAmount = 0;
            $tax = $project->taxes()->where('is_default', true)->first();
            if ($tax) {
                $taxAmount = ($subtotal * $tax->percentage) / 100;
            }
            
            // Update order
            $totalAmount = $subtotal + $taxAmount + $order->shipping_cost;
            
            $order->subtotal = $subtotal;
            $order->tax_amount = $taxAmount;
            $order->total_amount = $totalAmount;
            
            // Update remaining payment if down payment
            if ($order->payment_type === 'down_payment' && $order->down_payment_amount !== null) {
                $order->remaining_payment = $totalAmount - $order->down_payment_amount;
            }
            
            $order->save();
            
            // Update invoice if exists
            if ($order->invoice) {
                $this->invoiceService->updateInvoice($order->invoice);
            }
            
            DB::commit();
            
            return redirect()->route('organization.orders.show', $order)
                ->with('success', 'Order items updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update order items: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order): RedirectResponse
    {
        $this->checkOrderOwnership($order);
        
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,partial,completed,cancelled',
        ]);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            $order->payment_status = $validated['payment_status'];
            $order->save();
            
            // Update invoice status
            if ($order->invoice) {
                switch ($validated['payment_status']) {
                    case 'completed':
                        $invoiceStatus = 'paid';
                        break;
                    case 'partial':
                        $invoiceStatus = 'partially_paid';
                        break;
                    case 'cancelled':
                        $invoiceStatus = 'cancelled';
                        break;
                    default:
                        $invoiceStatus = 'unpaid';
                }
                
                $order->invoice->update(['status' => $invoiceStatus]);
            }
            
            DB::commit();
            
            return redirect()->route('organization.orders.show', $order)
                ->with('success', 'Payment status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }

    /**
     * Check if the order belongs to the current organization.
     */
    protected function checkOrderOwnership(Order $order): void
    {
        $organization = Auth::user()->organization;
        
        // Check if the order's project belongs to this organization
        $projectBelongsToOrg = Project::where('id', $order->project_id)
            ->where('organization_id', $organization->id)
            ->exists();
            
        if (!$projectBelongsToOrg) {
            abort(403, 'Unauthorized access to order.');
        }
    }
}