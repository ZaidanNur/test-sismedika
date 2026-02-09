<?php
namespace App\Services;

use App\Models\Food;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

final class OrderService
{
    public function __construct(private OrderDetailService $orderDetailService)
    {
    }

    public function getAll()
    {
        return Order::with(['createdBy'])->get();
    }

    public function getById($id)
    {
        return Order::with(['createdBy', 'details.food'])->find($id);
    }

    public function getByUser($userId)
    {
        return Order::with(['createdBy'])
            ->where('created_by', $userId)
            ->get();
    }

    public function create($data)
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'created_by' => $data['created_by'],
                'table_id' => $data['table_id'],
            ]);

            if (isset($data['details']) && is_array($data['details'])) {
                foreach ($data['details'] as $detail) {
                    $food = Food::find($detail['food_id']);
                    
                    $this->orderDetailService->create([
                        'order_id' => $order->id,
                        'food_id' => $detail['food_id'],
                        'quantity' => $detail['quantity'],
                        'current_price' => $food->price
                    ]);
                }
            }

            Table::where('id', $data['table_id'])->update(['status' => 'occupied']);

            return $order->load(['createdBy', 'details.food']);
        });
    }

    public function update($id, $data)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return null;
        }

        $order->fill($data);

        if ($order->isDirty()) {
            $order->save();
        }
        
        return $order;
    }

    public function delete($id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return null;
        }

        $order->details()->delete();
        $order->delete();
        
        return $order;
    }
}
