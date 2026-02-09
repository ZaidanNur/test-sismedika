<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->id ?? 'Order' }}</title>
    @vite('resources/css/app.css')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* A5 size: 148mm x 210mm */
        @page {
            size: A5;
            margin: 0;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        
        .receipt-container {
            width: 148mm;
            min-height: 210mm;
            margin: 0 auto;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        @media screen {
            body {
                background-color: #f3f4f6;
                padding: 20px;
            }
            .receipt-container {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container bg-white overflow-hidden">
        {{-- Header - Restaurant Info --}}
        <div class="bg-gradient-to-br from-amber-500 via-orange-500 to-amber-600 text-white px-8 py-6">
            <div class="flex items-center justify-center gap-4">
                <div class="bg-white/20 rounded-full p-3">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <h1 class="text-2xl font-bold tracking-wide">RESTO SISMEDIKA</h1>
                    <p class="text-sm opacity-90">Jl. Contoh Alamat No. 123, Kota 12345</p>
                </div>
            </div>
        </div>

        {{-- Receipt Title --}}
        <div class="text-center py-4 border-b-2 border-dashed border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 uppercase tracking-widest">Receipt</h2>
        </div>

        {{-- Order Info --}}
        <div class="px-8 py-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">No. Order</span>
                        <p class="font-bold text-gray-800 text-lg">#{{ str_pad($order->id ?? 0, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-gray-500">Tanggal</span>
                        <p class="font-semibold text-gray-800">{{ $order->created_at?->format('d M Y') ?? '-' }}</p>
                        <p class="text-gray-600 text-xs">{{ $order->created_at?->format('H:i') ?? '' }} WIB</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Meja</span>
                        <p class="font-semibold text-gray-800">Meja {{ $order->table->id ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-gray-500">Kasir</span>
                        <p class="font-semibold text-gray-800">{{ $order->createdBy->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Details --}}
        <div class="px-8 py-2">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                <span class="flex-1 h-px bg-gray-200"></span>
                <span>Detail Pesanan</span>
                <span class="flex-1 h-px bg-gray-200"></span>
            </h3>
            
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-gray-500 font-medium">Item</th>
                        <th class="text-center py-2 text-gray-500 font-medium w-12">Qty</th>
                        <th class="text-right py-2 text-gray-500 font-medium">Harga</th>
                        <th class="text-right py-2 text-gray-500 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->details ?? [] as $detail)
                    <tr class="border-b border-gray-100">
                        <td class="py-3">
                            <p class="font-medium text-gray-800">{{ $detail->food->name ?? 'Menu Item' }}</p>
                        </td>
                        <td class="py-3 text-center text-gray-600">{{ $detail->quantity }}</td>
                        <td class="py-3 text-right text-gray-600">Rp {{ number_format($detail->current_price, 0, ',', '.') }}</td>
                        <td class="py-3 text-right font-semibold text-gray-800">Rp {{ number_format($detail->quantity * $detail->current_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-400">Tidak ada item pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Total Section --}}
        <div class="px-8 py-4">
            @php
                $subtotal = collect($order->details ?? [])->sum(fn($d) => $d->quantity * $d->current_price);
                $tax = $subtotal * 0.10;
                $total = $subtotal + $tax;
            @endphp
            
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Pajak (10%)</span>
                    <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-300 pt-3 mt-2">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">TOTAL</span>
                        <span class="text-xl font-bold text-amber-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="px-8 py-2">
            <div class="border border-green-200 bg-green-50 rounded-lg p-3 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-700 font-medium text-sm">Pembayaran Lunas</span>
            </div>
        </div>

        {{-- Footer - Contact Info --}}
        <div class="px-8 py-6 mt-auto">
            <div class="border-t-2 border-dashed border-gray-200 pt-4">
                <div class="text-center mb-4">
                    <p class="text-sm font-semibold text-gray-700">Hubungi Kami</p>
                </div>
                <div class="flex justify-center items-center gap-6 text-gray-600 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="bg-amber-100 rounded-full p-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <span>0812-3456-7890</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-amber-100 rounded-full p-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </div>
                        <span>@restosismedika</span>
                    </div>
                </div>
                
                <div class="text-center mt-6 pt-4 border-t border-gray-100">
                    <p class="text-gray-600 font-medium">Terima kasih atas kunjungan Anda!</p>
                    <p class="text-gray-400 text-sm mt-1">Sampai jumpa kembali 🙏</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Print & Download Buttons (Screen Only) --}}
    <div class="no-print max-w-md mx-auto mt-6 px-4 flex gap-3">
        <button onclick="window.print()" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print
        </button>
        <a href="{{ route('receipt.download', $order) }}" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Download PDF
        </a>
    </div>
</body>
</html>
