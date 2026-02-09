<?php
namespace App\Services;

use App\Models\FoodCategory;

final class FoodCategoryService
{
    public function getAll()
    {
        return FoodCategory::with('foods')->get();
    }

    public function create($data)
    {
        return FoodCategory::create($data);
    }

    public function update($id, $data)
    {
        $category = FoodCategory::find($id);
        
        if (!$category) {
            return null;
        }

        $category->fill($data);

        if ($category->isDirty()) {
            $category->save();
        }
        
        return $category;
    }

    public function delete($id)
    {
        $category = FoodCategory::find($id);
        
        if ($category) {
            $category->delete();
        }
        
        return $category;
    }
}
