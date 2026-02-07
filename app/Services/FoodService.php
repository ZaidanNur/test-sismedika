<?php
namespace App\Services;

use App\Models\Food;

final class FoodService
{
    public function getAll()
    {
        return Food::all();
    }

    public function getById($id)
    {
        return Food::find($id);
    }

    public function create($data)
    {
        return Food::create($data);
    }

    public function update($id, $data)
    {
        $food = Food::find($id);
        $food->update($data);
        return $food;
    }

    public function delete($id)
    {
        $food = Food::find($id);
        $food->delete();
        return $food;
    }
}
