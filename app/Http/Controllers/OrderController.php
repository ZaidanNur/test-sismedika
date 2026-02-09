<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $order->load(['details.food', 'table', 'createdBy']);
        return view('receipt', compact('order'));
    }

    public function download(Order $order)
    {
        $order->load(['details.food', 'table', 'createdBy']);
        
        $date = now()->format('Ymd');
        $time = now()->format('His');
        $tableId = $order->table_id ?? 0;
        $randomString = \Illuminate\Support\Str::random(8);
        
        $filename = "{$date}_{$time}_{$tableId}_{$randomString}.pdf";
        
        return Pdf::view('receipt', ['order' => $order])
            ->format('a5')
            ->name($filename)
            ->download($filename);
    }
}
