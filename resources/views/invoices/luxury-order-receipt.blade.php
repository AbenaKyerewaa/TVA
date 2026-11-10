<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>The Vaughn Atelier &mdash; Invoice {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 28mm 20mm 25mm 20mm;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Serif', Georgia, serif;
            color: #1a1a1a;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .sans {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #b4975a;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .brand-title {
            font-size: 26px;
            letter-spacing: 4px;
            font-weight: bold;
            color: #111111;
            text-transform: uppercase;
            margin: 0;
        }
        .brand-sub {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #b4975a;
            margin-top: 4px;
        }
        .invoice-title-block {
            text-align: right;
        }
        .invoice-title {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #111111;
            margin: 0;
        }
        .meta-text {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            color: #555555;
            margin-top: 3px;
        }
        .addresses-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .address-box {
            width: 48%;
            vertical-align: top;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.5;
        }
        .box-title {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #888888;
            margin-bottom: 6px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 3px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #111111;
            background-color: #f7f6f2;
            border-top: 1px solid #b4975a;
            border-bottom: 1px solid #b4975a;
            padding: 8px 10px;
            text-align: left;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eeeeee;
            font-size: 10px;
            vertical-align: top;
        }
        .item-name {
            font-weight: bold;
            color: #111111;
            font-size: 11px;
        }
        .item-variant {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.5px;
            color: #666666;
            margin-top: 2px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }
        .totals-table td {
            padding: 5px 8px;
        }
        .totals-table .total-row {
            font-size: 13px;
            font-weight: bold;
            border-top: 1px solid #b4975a;
            border-bottom: 2px solid #b4975a;
            color: #111111;
        }
        .certificate-seal {
            border: 1px solid #e0d5ba;
            background-color: #faf8f5;
            padding: 12px 16px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.6;
            color: #555555;
            margin-top: 20px;
        }
        .seal-title {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #8c733e;
            font-size: 9px;
            margin-bottom: 3px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #999999;
            border-top: 1px solid #eeeeee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: middle;">
                <div class="brand-title">The Vaughn Atelier</div>
                <div class="brand-sub">Haute Joaillerie &bull; Paris &bull; London &bull; New York</div>
            </td>
            <td class="invoice-title-block" style="vertical-align: middle;">
                <div class="invoice-title">Tax Invoice</div>
                <div class="meta-text"><strong>Reference:</strong> {{ $order->order_number }}</div>
                <div class="meta-text"><strong>Date of Issue:</strong> {{ $order->created_at->format('d F Y') }}</div>
                <div class="meta-text"><strong>Settlement Status:</strong> <span style="text-transform: uppercase; color: #1e7e34; font-weight: bold;">{{ $order->payment_status }}</span></div>
            </td>
        </tr>
    </table>

    <!-- Addresses -->
    <table class="addresses-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="address-box">
                <div class="box-title">Client & Billing Information</div>
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_email }}<br>
                Phone: {{ $order->customer_phone ?? 'On file' }}
            </td>
            <td style="width: 4%;"></td>
            <td class="address-box">
                <div class="box-title">Insured White-Glove Consignment</div>
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->shipping_address_line1 }}<br>
                @if($order->shipping_address_line2)
                    {{ $order->shipping_address_line2 }}<br>
                @endif
                {{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }} {{ $order->shipping_postal_code }}<br>
                {{ $order->shipping_country }}
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
    <table class="items-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 50%;">Commissioned Masterpiece</th>
                <th class="text-center" style="width: 15%;">Quantity</th>
                <th class="text-right" style="width: 15%;">Unit Price</th>
                <th class="text-right" style="width: 20%;">Amount (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item->product_name }}</div>
                        @if($item->variant_name)
                            <div class="item-variant">Specification: {{ $item->variant_name }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right font-weight: bold;">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Table -->
    <table class="totals-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="color: #666666;">Subtotal:</td>
            <td class="text-right">${{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($order->discount_amount > 0)
            <tr>
                <td style="color: #1e7e34;">Collector Privilege Discount:</td>
                <td class="text-right" style="color: #1e7e34;">-${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td style="color: #666666;">Insured Vault Transit:</td>
            <td class="text-right">{{ $order->shipping_fee > 0 ? '$'.number_format($order->shipping_fee, 2) : 'Complimentary' }}</td>
        </tr>
        <tr>
            <td style="color: #666666;">Estimated Tax:</td>
            <td class="text-right">${{ number_format($order->tax_amount, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Settled:</td>
            <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <!-- Authenticity & Security Guarantee -->
    <div class="certificate-seal">
        <div class="seal-title">Authenticity & Provenance Certified</div>
        <div>
            This document constitutes the official bill of sale and certificate of provenance for the handcrafted jewelry registered under order <strong>{{ $order->order_number }}</strong>. Every gemstone is conflict-free and verified according to the Kimberley Process and GIA specifications. Underwritten with comprehensive Lloyd's of London transit insurance.
        </div>
        <div style="margin-top: 6px; font-size: 8px; color: #888888;">
            Payment Method: {{ strtoupper($order->payment_method ?? 'Vault Settlement') }} &bull; 
            @if($order->payments->isNotEmpty())
                Transaction Reference: {{ $order->payments->first()->transaction_reference }} &bull;
            @endif
            Settlement Timestamp: {{ $order->paid_at ? $order->paid_at->format('Y-m-d H:i:s') : 'Pending' }}
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        The Vaughn Atelier SAS &bull; 14 Place Vendôme, 75001 Paris, France &bull; VAT: FR 88 920 184 721 &bull; concierge@thevaughnatelier.com
    </div>

</body>
</html>

