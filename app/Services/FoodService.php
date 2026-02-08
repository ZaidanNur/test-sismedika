<?php
namespace App\Services;

use App\Models\Food;

final class FoodService
{
    public function getAll($categoryId = null, $search = null)
    {
        $query = Food::with('category');

        if ($categoryId) {
            $query->where('food_category_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->get();
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
        $food->fill($data);

        if ($food->isDirty()) {
            $food->save();
        }
        return $food;
    }

    public function delete($id)
    {
        $food = Food::find($id);
        $food->delete();
        return $food;
    }
}
