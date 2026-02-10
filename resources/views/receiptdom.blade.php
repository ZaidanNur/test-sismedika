<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->id ?? 'Order' }}</title>
    <style>
        @page {
            size: A5;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1f2937;
            font-size: 13px;
            line-height: 1.5;
        }

        .receipt-container {
            width: 148mm;
            min-height: 210mm;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .header {
            background-color: #f59e0b;
            color: #ffffff;
            padding: 24px 32px;
            text-align: center;
        }
        .header-inner {
            display: table;
            margin: 0 auto;
        }
        .header-icon-cell {
            display: table-cell;
            vertical-align: middle;
            padding-right: 14px;
        }
        .header-icon {
            width: 44px;
            height: 44px;
            background-color: rgba(255,255,255,0.2);
            border-radius: 50%;
            text-align: center;
            padding: 8px;
        }
        .header-icon svg {
            width: 28px;
            height: 28px;
        }
        .header-text-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: left;
        }
        .header-title {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
        }
        .header-address {
            font-size: 12px;
            opacity: 0.9;
            margin: 2px 0 0 0;
        }

        /* Receipt Title */
        .receipt-title {
            text-align: center;
            padding: 14px 0;
            border-bottom: 2px dashed #e5e7eb;
        }
        .receipt-title h2 {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 0;
        }

        /* Order Info */
        .order-info-wrapper {
            padding: 16px 32px;
        }
        .order-info-box {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 14px 16px;
        }
        .order-info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            font-size: 12px;
            color: #6b7280;
        }
        .info-value {
            font-weight: 600;
            color: #1f2937;
            margin: 2px 0 0 0;
        }
        .info-value-lg {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin: 2px 0 0 0;
        }
        .info-sub {
            font-size: 11px;
            color: #4b5563;
        }

        /* Section Divider */
        .section-divider {
            padding: 0 32px;
            margin-bottom: 10px;
        }
        .divider-inner {
            display: table;
            width: 100%;
        }
        .divider-line {
            display: table-cell;
            vertical-align: middle;
            width: 40%;
        }
        .divider-line-inner {
            border-bottom: 1px solid #e5e7eb;
            height: 1px;
        }
        .divider-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 0 10px;
            white-space: nowrap;
        }

        /* Order Details Table */
        .order-details-wrapper {
            padding: 0 32px 8px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .details-table thead th {
            text-align: left;
            padding: 8px 0;
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-table thead th.text-center {
            text-align: center;
        }
        .details-table thead th.text-right {
            text-align: right;
        }
        .details-table tbody td {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .details-table tbody td.text-center {
            text-align: center;
            color: #4b5563;
        }
        .details-table tbody td.text-right {
            text-align: right;
            color: #4b5563;
        }
        .details-table tbody td.text-right.bold {
            font-weight: 600;
            color: #1f2937;
        }
        .item-name {
            font-weight: 500;
            color: #1f2937;
        }
        .empty-row td {
            padding: 24px 0;
            text-align: center;
            color: #9ca3af;
        }

        /* Total Section */
        .total-wrapper {
            padding: 8px 32px 16px;
        }
        .total-box {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 14px 16px;
        }
        .total-row {
            width: 100%;
            border-collapse: collapse;
        }
        .total-row td {
            padding: 4px 0;
            font-size: 13px;
            color: #4b5563;
        }
        .total-row td.text-right {
            text-align: right;
        }
        .total-grand {
            border-top: 1px solid #d1d5db;
            padding-top: 10px;
            margin-top: 6px;
        }
        .total-grand-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-grand-table td {
            padding-top: 10px;
        }
        .total-label {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        .total-value {
            font-size: 18px;
            font-weight: bold;
            color: #d97706;
            text-align: right;
        }

        /* Payment Info */
        .payment-wrapper {
            padding: 0 32px 8px;
        }
        .payment-box {
            border: 1px solid #bbf7d0;
            background-color: #f0fdf4;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }
        .payment-text {
            color: #15803d;
            font-weight: 500;
            font-size: 13px;
        }
        .payment-icon {
            color: #16a34a;
            font-size: 16px;
            font-weight: bold;
        }

        /* Footer */
        .footer-wrapper {
            padding: 16px 32px 24px;
        }
        .footer-divider {
            border-top: 2px dashed #e5e7eb;
            padding-top: 16px;
        }
        .footer-contact-title {
            text-align: center;
            margin-bottom: 12px;
        }
        .footer-contact-title p {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }
        .footer-contacts {
            text-align: center;
            font-size: 12px;
            color: #4b5563;
        }
        .footer-contact-item {
            display: inline-block;
            margin: 0 12px;
        }
        .contact-icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background-color: #fef3c7;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-size: 12px;
            color: #d97706;
            vertical-align: middle;
            margin-right: 6px;
        }
        .footer-thanks {
            text-align: center;
            margin-top: 20px;
            padding-top: 14px;
            border-top: 1px solid #f3f4f6;
        }
        .thanks-text {
            color: #4b5563;
            font-weight: 500;
            font-size: 13px;
        }
        .thanks-sub {
            color: #9ca3af;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Print / Screen Specific */
        @media print {
            .no-print {
                display: none !important;
            }
        }

        @media screen {
            body {
                background-color: #f3f4f6;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        {{-- Header - Restaurant Info --}}
        <div class="header">
            <div class="header-inner">
                <div class="header-icon-cell">
                    <div class="header-icon">
                        <svg fill="white" viewBox="0 0 24 24">
                            <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
                        </svg>
                    </div>
                </div>
                <div class="header-text-cell">
                    <h1 class="header-title">RESTO SISMEDIKA</h1>
                    <p class="header-address">Jl. Contoh Alamat No. 123, Kota 12345</p>
                </div>
            </div>
        </div>

        {{-- Receipt Title --}}
        <div class="receipt-title">
            <h2>Receipt</h2>
        </div>

        {{-- Order Info --}}
        <div class="order-info-wrapper">
            <div class="order-info-box">
                <table class="order-info-table">
                    <tr>
                        <td style="width: 50%;">
                            <span class="info-label">No. Order</span>
                            <p class="info-value-lg">#{{ str_pad($order->id ?? 0, 5, '0', STR_PAD_LEFT) }}</p>
                        </td>
                        <td style="width: 50%; text-align: right;">
                            <span class="info-label">Tanggal</span>
                            <p class="info-value">{{ $order->created_at?->format('d M Y') ?? '-' }}</p>
                            <span class="info-sub">{{ $order->created_at?->format('H:i') ?? '' }} WIB</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="info-label">Meja</span>
                            <p class="info-value">Meja {{ $order->table->id ?? '-' }}</p>
                        </td>
                        <td style="text-align: right;">
                            <span class="info-label">Kasir</span>
                            <p class="info-value">{{ $order->createdBy->name ?? '-' }}</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Section Divider --}}
        <div class="section-divider">
            <div class="divider-inner">
                <div class="divider-line"><div class="divider-line-inner"></div></div>
                <div class="divider-text">Detail Pesanan</div>
                <div class="divider-line"><div class="divider-line-inner"></div></div>
            </div>
        </div>

        {{-- Order Details --}}
        <div class="order-details-wrapper">
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center" style="width: 50px;">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->details ?? [] as $detail)
                    <tr>
                        <td>
                            <span class="item-name">{{ $detail->food->name ?? 'Menu Item' }}</span>
                        </td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($detail->current_price, 0, ',', '.') }}</td>
                        <td class="text-right bold">Rp {{ number_format($detail->quantity * $detail->current_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="4">Tidak ada item pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Total Section --}}
        <div class="total-wrapper">
            @php
                $subtotal = collect($order->details ?? [])->sum(fn($d) => $d->quantity * $d->current_price);
                $tax = $subtotal * 0.10;
                $total = $subtotal + $tax;
            @endphp

            <div class="total-box">
                <table class="total-row">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Pajak (10%)</td>
                        <td class="text-right">Rp {{ number_format($tax, 0, ',', '.') }}</td>
                    </tr>
                </table>
                <div class="total-grand">
                    <table class="total-grand-table">
                        <tr>
                            <td class="total-label">TOTAL</td>
                            <td class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="payment-wrapper">
            <div class="payment-box">
                <span class="payment-icon">&#10003;</span>
                <span class="payment-text">Pembayaran Lunas</span>
            </div>
        </div>

        {{-- Footer - Contact Info --}}
        <div class="footer-wrapper">
            <div class="footer-divider">
                <div class="footer-contact-title">
                    <p>Hubungi Kami</p>
                </div>
                <div class="footer-contacts">
                    <span class="footer-contact-item">
                        <span class="contact-icon">&#9742;</span>
                        0812-3456-7890
                    </span>
                    <span class="footer-contact-item">
                        <span class="contact-icon">&#9679;</span>
                        @restosismedika
                    </span>
                </div>

                <div class="footer-thanks">
                    <p class="thanks-text">Terima kasih atas kunjungan Anda!</p>
                    <p class="thanks-sub">Sampai jumpa kembali</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Print & Download Buttons (Screen Only) --}}
    <div class="no-print" style="max-width: 400px; margin: 20px auto; padding: 0 16px; text-align: center;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 8px 0;">
            <tr>
                <td style="width: 50%;">
                    <button onclick="window.print()" style="width: 100%; background-color: #1f2937; color: #ffffff; font-weight: 600; padding: 12px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px;">
                        Print
                    </button>
                </td>
                <td style="width: 50%;">
                    <a href="{{ route('receipt.download', $order) }}" style="display: block; width: 100%; background-color: #f59e0b; color: #ffffff; font-weight: 600; padding: 12px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; text-align: center;">
                        Download PDF
                    </a>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
