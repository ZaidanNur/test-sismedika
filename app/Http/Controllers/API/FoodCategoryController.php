<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\FoodCategoryService;
use App\Http\Controllers\Controller;

class FoodCategoryController extends Controller
{
    public function __construct(private FoodCategoryService $foodCategoryService)
    {
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Food category list',
            'data' => $this->foodCategoryService->getAll(),
        ]);
    }

    public function show($id)
    {
        $category = $this->foodCategoryService->getById($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Food category not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Food category details',
            'data' => $category,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = $this->foodCategoryService->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Food category created',
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = $this->foodCategoryService->update($id, $request->all());

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Food category not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Food category updated',
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = $this->foodCategoryService->delete($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Food category not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Food category deleted',
            'data' => $category,
        ]);
    }
}
