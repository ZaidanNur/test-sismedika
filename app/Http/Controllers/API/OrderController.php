<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Http\Controllers\Controller;
use App\Services\OrderDetailService;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\OrderDetailUpdateRequest;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private OrderDetailService $orderDetailService
    ) {
    }

    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        if ($userId) {
            $data = $this->orderService->getByUser($userId);
        } else {
            $data = $this->orderService->getAll();
        }

        return response()->json([
            'success' => true,
            'message' => 'Order list',
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $order = $this->orderService->getById($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order details',
            'data' => $order
        ]);
    }

    public function store(OrderCreateRequest $request)
    {
        $table = Table::find($request->table_id);

        if (!$table || $table->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Table is not available',
            ], 400);
        }

        $data = $request->all();

        $data['created_by'] = $request->user()->id;

        $order = $this->orderService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Order created',
            'data' => $order,
        ], 201);
    }

    public function update(OrderUpdateRequest $request, $id)
    {       
        // dd($request->all());
        $order = $this->orderService->getById($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $updatedOrder = $this->orderService->update($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Order updated',
            'data' => $updatedOrder,
        ]);
    }

    public function destroy($id)
    {
        $order = $this->orderService->getById($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $this->orderService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Order deleted',
        ]);
    }

    public function updateDetail(OrderDetailUpdateRequest $request, $orderId, $detailId)
    {
        $order = $this->orderService->getById($orderId);

        if (!$order || $order->status == 'closed' || $order->status == 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or already closed or cancelled',
            ], 404);
        }

        $orderDetail = $this->orderDetailService->getById($detailId);

        if (!$orderDetail || $orderDetail->order_id != $orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Order detail not found',
            ], 404);
        }

        $data = $request->all();

        if ($request->has('food_id')) {
            $food = \App\Models\Food::find($request->food_id);
            $data['current_price'] = $food->price;
        }

        $updatedDetail = $this->orderDetailService->update($detailId, $data);

        return response()->json([
            'success' => true,
            'message' => 'Order detail updated',
            'data' => $updatedDetail->load('food'),
        ]);
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
