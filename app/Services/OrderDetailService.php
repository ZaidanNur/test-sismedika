<?php
namespace App\Services;

use App\Models\OrderDetail;

final class OrderDetailService
{
    public function getAll()
    {
        return OrderDetail::with(['order', 'food'])->get();
    }

    public function getById($id)
    {
        return OrderDetail::with(['order', 'food'])->find($id);
    }

    public function getByOrderId($orderId)
    {
        return OrderDetail::with(['food'])
            ->where('order_id', $orderId)
            ->get();
    }

    public function create($data)
    {
        return OrderDetail::create($data);
    }

    public function update($id, $data)
    {
        $orderDetail = OrderDetail::find($id);
        
        if (!$orderDetail) {
            return null;
        }

        $orderDetail->fill($data);

        if ($orderDetail->isDirty()) {
            $orderDetail->save();
        }
        
        return $orderDetail;
    }

    public function delete($id)
    {
        $orderDetail = OrderDetail::find($id);
        
        if (!$orderDetail) {
            return null;
        }

        $orderDetail->delete();
        
        return $orderDetail;
    }

    public function deleteByOrderId($orderId)
    {
        return OrderDetail::where('order_id', $orderId)->delete();
    }
}
