<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0.5cm;
        }
        
        body {
            font-family: {{ $invoice->template->font_family ?? 'Arial, sans-serif' }};
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #333;
            background-color: #fff;
        }
        
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 20px;
        }
        
        .company-info {
            max-width: 60%;
        }
        
        .company-logo {
            max-height: 80px;
            max-width: 200px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: {{ $invoice->template->primary_color ?? '#0284c7' }};
            margin: 5px 0;
        }
        
        .company-details {
            margin-top: 5px;
            line-height: 1.4;
        }
        
        .invoice-info {
            text-align: {{ $invoice->template->text_alignment ?? 'right' }};
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: {{ $invoice->template->primary_color ?? '#0284c7' }};
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .invoice-details {
            line-height: 1.6;
        }
        
        .invoice-details strong {
            color: #333;
        }
        
        /* Customer Information */
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .billing-info, .shipping-info {
            width: 48%;
        }
        
        .billing-info h3, .shipping-info h3 {
            font-size: 14px;
            color: {{ $invoice->template->primary_color ?? '#0284c7' }};
            margin-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 5px;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background-color: {{ $invoice->template->primary_color ?? '#0284c7' }};
            color: #fff;
            font-weight: bold;
            text-align: left;
            padding: 10px;
        }
        
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .items-table .item-name {
            width: 40%;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* Summary Table */
        .summary-table {
            width: 100%;
            margin-bottom: 30px;
        }
        
        .summary-table td {
            padding: 5px 10px;
        }
        
        .summary-table .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #333;
        }
        
        .summary-table .text-right {
            text-align: right;
        }
        
        /* Payment Information */
        .payment-info {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .payment-info h3 {
            font-size: 14px;
            color: {{ $invoice->template->primary_color ?? '#0284c7' }};
            margin-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 5px;
        }
        
        .payment-method-logo {
            max-height: 40px;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        .payment-instructions {
            margin-top: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #eaeaea;
            border-radius: 3px;
        }
        
        /* Notes */
        .notes {
            margin-bottom: 30px;
        }
        
        .notes h3 {
            font-size: 14px;
            color: {{ $invoice->template->primary_color ?? '#0284c7' }};
            margin-bottom: 10px;
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 5px;
        }
        
        /* Terms and Signature */
        .terms-signature {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .terms {
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eaeaea;
        }
        
        /* Utilities */
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-primary {
            color: {{ $invoice->template->primary_color ?? '#0284c7' }};
        }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            color: #fff;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-paid {
            background-color: #10b981;
        }
        
        .badge-pending {
            background-color: #f59e0b;
        }
        
        .badge-overdue {
            background-color: #ef4444;
        }
        
        .badge-cancelled {
            background-color: #6b7280;
        }
        
        .badge-partially-paid {
            background-color: #8b5cf6;
        }
        
        .badge-draft {
            background-color: #6b7280;
        }
        
        hr {
            border: 0;
            height: 1px;
            background: #eaeaea;
            margin: 20px 0;
        }
        
        .stamp {
            position: relative;
            margin-top: 20px;
            padding: 0;
        }
        
        .stamp-paid {
            position: absolute;
            right: 20%;
            top: -80px;
            color: #10b981;
            border: 4px solid #10b981;
            transform: rotate(-15deg);
            font-size: 28px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 6px;
            opacity: 0.5;
        }
        
        .stamp-overdue {
            position: absolute;
            right: 20%;
            top: -80px;
            color: #ef4444;
            border: 4px solid #ef4444;
            transform: rotate(-15deg);
            font-size: 28px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 6px;
            opacity: 0.5;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="company-info">
                @if($invoice->template->show_organization_logo && $organization->logo)
                <img src="{{ public_path('storage/' . $organization->logo) }}" alt="{{ $organization->name }}" class="company-logo">
                @else
                <div class="company-name">{{ $organization->name }}</div>
                @endif
                <div class="company-details">
                    {{ $organization->address }}<br>
                    @if($organization->phone)
                    Phone: {{ $organization->phone }}<br>
                    @endif
                    @if($organization->email)
                    Email: {{ $organization->email }}<br>
                    @endif
                    @if($organization->website)
                    Website: {{ $organization->website }}<br>
                    @endif
                    @if($organization->tax_id)
                    Tax ID: {{ $organization->tax_id }}
                    @endif
                </div>
            </div>
            
            <div class="invoice-info">
                <div class="invoice-title">
                    {{ $invoice->template->header_text ?? 'INVOICE' }}
                </div>
                <div class="invoice-details">
                    <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Order #:</strong> {{ $invoice->order->order_number }}<br>
                    <strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}<br>
                    @if($invoice->due_date)
                    <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                    @endif
                    <strong>Status:</strong> 
                    <span class="badge badge-{{ str_replace('_', '-', $invoice->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Billing & Shipping Section -->
        <div class="billing-section">
            <div class="billing-info">
                <h3>BILL TO</h3>
                <strong>{{ $invoice->order->customer->name }}</strong><br>
                @if($invoice->order->customer->email)
                {{ $invoice->order->customer->email }}<br>
                @endif
                @if($invoice->order->customer->phone_number)
                {{ $invoice->order->customer->phone_number }}<br>
                @endif
                @if($invoice->order->customer->address)
                {{ $invoice->order->customer->address }}<br>
                @endif
                @if($invoice->order->customer->city)
                {{ $invoice->order->customer->city }}
                @if($invoice->order->customer->postal_code)
                , {{ $invoice->order->customer->postal_code }}
                @endif
                <br>
                @endif
                @if($invoice->order->customer->state)
                {{ $invoice->order->customer->state }}
                @if($invoice->order->customer->country)
                , {{ $invoice->order->customer->country }}
                @endif
                @elseif($invoice->order->customer->country)
                {{ $invoice->order->customer->country }}
                @endif
            </div>
            
            <div class="shipping-info">
                <h3>PROJECT</h3>
                <strong>{{ $invoice->order->project->name }}</strong><br>
                @if($invoice->order->project->description)
                {{ Illuminate\Support\Str::limit($invoice->order->project->description, 100) }}<br>
                @endif
                <strong>Type:</strong> {{ ucfirst($invoice->order->project->type) }}<br>
                @if($invoice->order->project->start_date)
                <strong>Start Date:</strong> {{ $invoice->order->project->start_date->format('M d, Y') }}<br>
                @endif
                @if($invoice->order->project->end_date)
                <strong>End Date:</strong> {{ $invoice->order->project->end_date->format('M d, Y') }}
                @endif
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="item-name">Description</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->order->orderItems as $item)
                <tr>
                    <td>{{ $item->projectProduct->product->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->discount > 0 ? 'Rp ' . number_format($item->discount, 0, ',', '.') : '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary Table -->
        <div style="width: 100%">
            <table class="summary-table" style="margin-left: auto; width: 50%;">
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">Rp {{ number_format($invoice->order->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->order->discount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">- Rp {{ number_format($invoice->order->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($invoice->order->tax_amount > 0)
                <tr>
                    <td>Tax ({{ $invoice->order->tax_percentage }}%):</td>
                    <td class="text-right">Rp {{ number_format($invoice->order->tax_amount, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($invoice->order->shipping_cost > 0)
                <tr>
                    <td>Shipping:</td>
                    <td class="text-right">Rp {{ number_format($invoice->order->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total:</td>
                    <td class="text-right">Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}</td>
                </tr>
                @if($invoice->order->payment_type === 'down_payment')
                <tr>
                    <td>Down Payment ({{ $invoice->order->down_payment_percentage }}%):</td>
                    <td class="text-right">Rp {{ number_format($invoice->order->down_payment_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Remaining Payment:</td>
                    <td class="text-right">Rp {{ number_format($invoice->order->remaining_payment, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($invoice->paid_amount > 0)
                <tr>
                    <td>Amount Paid:</td>
                    <td class="text-right">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Balance Due:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($invoice->order->total_amount - $invoice->paid_amount, 0, ',', '.') }}</strong></td>
                </tr>
                @endif
            </table>
        </div>
        
        <!-- Payment Information -->
        @if($invoice->template->show_payment_instructions)
        <div class="payment-info">
            <h3>PAYMENT INFORMATION</h3>
            <div>
                @if($invoice->template->show_payment_method_logo && $invoice->order->paymentMethod->logo)
                <img src="{{ public_path('storage/' . $invoice->order->paymentMethod->logo) }}" alt="{{ $invoice->order->paymentMethod->name }}" class="payment-method-logo">
                @endif
                <strong>{{ $invoice->order->paymentMethod->name }}</strong>
            </div>
            
            @if($invoice->order->paymentMethod->payment_type === 'bank_transfer')
            <div style="margin-top: 10px;">
                <strong>Bank Name:</strong> {{ $invoice->order->paymentMethod->bank_name }}<br>
                <strong>Account Number:</strong> {{ $invoice->order->paymentMethod->account_number }}<br>
                <strong>Account Name:</strong> {{ $invoice->order->paymentMethod->account_name }}
            </div>
            @endif
            
            @if($invoice->order->paymentMethod->instructions)
            <div class="payment-instructions">
                {{ $invoice->order->paymentMethod->instructions }}
            </div>
            @endif
        </div>
        @endif
        
        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <h3>NOTES</h3>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif
        
        <!-- Terms & Signature -->
        <div class="terms-signature">
            @if($invoice->template->include_terms && $invoice->template->terms_text)
            <div class="terms">
                <h3>TERMS & CONDITIONS</h3>
                <p>{{ $invoice->template->terms_text }}</p>
            </div>
            @endif
            
            @if($invoice->template->include_signature)
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Customer Signature</div>
                </div>
                
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>{{ $organization->name }}</div>
                </div>
            </div>
            @endif
            
            @if($invoice->template->include_stamp)
            <div class="stamp">
                @if($invoice->status === 'paid')
                <div class="stamp-paid">PAID</div>
                @elseif($invoice->status === 'overdue')
                <div class="stamp-overdue">OVERDUE</div>
                @endif
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            @if($invoice->template->footer_text)
                {{ $invoice->template->footer_text }}
            @else
                Thank you for your business!
            @endif
            <br>
            <span style="font-size: 9px;">Generated on {{ now()->format('M d, Y H:i:s') }} by Invoice Automation System</span>
        </div>
    </div>
</body>
</html>