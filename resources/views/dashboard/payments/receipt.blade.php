<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->receipt_number ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background: #f5f5f5;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .receipt-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .receipt-header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .receipt-title {
            background: #f8f9fa;
            padding: 15px 30px;
            border-bottom: 2px solid #10b981;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .receipt-title h2 {
            font-size: 18px;
            color: #333;
        }
        
        .receipt-number {
            font-size: 14px;
            color: #666;
        }
        
        .receipt-number strong {
            color: #10b981;
        }
        
        .receipt-body {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-section h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-item label {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .info-item span {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }
        
        .amount-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        
        .amount-section label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }
        
        .amount-section .amount {
            font-size: 36px;
            font-weight: bold;
            color: #10b981;
        }
        
        .amount-section .method {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .payment-details {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            margin: 25px 0;
        }
        
        .payment-details table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .payment-details th,
        .payment-details td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .payment-details th {
            background: #f8f9fa;
            font-size: 11px;
            text-transform: uppercase;
            color: #888;
            font-weight: 600;
        }
        
        .payment-details td {
            font-size: 14px;
        }
        
        .payment-details tr:last-child td {
            border-bottom: none;
        }
        
        .receipt-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #eee;
        }
        
        .receipt-footer p {
            font-size: 12px;
            color: #888;
            margin-bottom: 5px;
        }
        
        .receipt-footer .thank-you {
            font-size: 14px;
            color: #10b981;
            font-weight: 600;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
        }
        
        .signature-label {
            font-size: 12px;
            color: #666;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
        }
        
        .print-button:hover {
            background: #059669;
        }
        
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #6b7280;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        
        .back-button:hover {
            background: #4b5563;
        }
        
        @media print {
            body {
                background: white;
            }
            
            .receipt-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            
            .print-button,
            .back-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('dashboard.payments.show', $payment) }}" class="back-button">← Back</a>
    <button onclick="window.print()" class="print-button">🖨️ Print Receipt</button>
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h1>{{ $institutionName }}</h1>
            @if($institutionAddress)
                <p>{{ $institutionAddress }}</p>
            @endif
            @if($institutionPhone || $institutionEmail)
                <p>
                    @if($institutionPhone) Phone: {{ $institutionPhone }} @endif
                    @if($institutionPhone && $institutionEmail) | @endif
                    @if($institutionEmail) Email: {{ $institutionEmail }} @endif
                </p>
            @endif
        </div>
        
        <!-- Receipt Title -->
        <div class="receipt-title">
            <h2>PAYMENT RECEIPT</h2>
            <div class="receipt-number">
                Receipt No: <strong>{{ $payment->receipt_number ?? 'N/A' }}</strong>
            </div>
        </div>
        
        <!-- Receipt Body -->
        <div class="receipt-body">
            <!-- Student Information -->
            <div class="info-section">
                <h3>Student Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Student Name</label>
                        <span>{{ $payment->student->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Registration No.</label>
                        <span>{{ $payment->student->registration_no ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Batch</label>
                        <span>{{ $payment->student->batch->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Contact</label>
                        <span>{{ $payment->student->phone ?? $payment->student->user->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Payment Amount -->
            <div class="amount-section">
                <label>Amount Paid</label>
                <div class="amount">৳{{ number_format($payment->amount, 2) }}</div>
                <div class="method">via {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
            </div>
            
            <!-- Payment Details -->
            <div class="payment-details">
                <table>
                    <tr>
                        <th>Description</th>
                        <th>Details</th>
                    </tr>
                    <tr>
                        <td>Payment Date</td>
                        <td>{{ $payment->payment_date ? $payment->payment_date->format('F d, Y') : now()->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td>Payment Method</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    </tr>
                    @if($payment->transaction_id)
                    <tr>
                        <td>Transaction ID</td>
                        <td>{{ $payment->transaction_id }}</td>
                    </tr>
                    @endif
                    @if($payment->invoice)
                    <tr>
                        <td>Invoice Reference</td>
                        <td>{{ $payment->invoice->invoice_number }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Payment Status</td>
                        <td>{{ ucfirst($payment->status) }}</td>
                    </tr>
                    @if($payment->notes)
                    <tr>
                        <td>Notes</td>
                        <td>{{ $payment->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <!-- Balance Information -->
            <div class="info-section">
                <h3>Account Summary</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Total Course Fee</label>
                        <span>৳{{ number_format($payment->student->total_amount, 2) }}</span>
                    </div>
                    <div class="info-item">
                        <label>Total Paid</label>
                        <span style="color: #10b981;">৳{{ number_format($payment->student->paid_amount, 2) }}</span>
                    </div>
                    <div class="info-item">
                        <label>Due Amount</label>
                        <span style="color: {{ $payment->student->due_amount > 0 ? '#ef4444' : '#10b981' }};">
                            ৳{{ number_format($payment->student->due_amount, 2) }}
                        </span>
                    </div>
                    <div class="info-item">
                        <label>Receipt Generated</label>
                        <span>{{ now()->format('F d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Student Signature</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Authorized Signature</div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="receipt-footer">
            <p class="thank-you">Thank you for your payment!</p>
            <p>This is a computer-generated receipt. No signature required.</p>
            <p>For any queries, please contact the administration office.</p>
        </div>
    </div>
</body>
</html>
