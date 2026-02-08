<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\FoodService;
use App\Http\Controllers\Controller;
use App\Http\Requests\FoodCreateRequest;
use App\Http\Requests\FoodUpdateRequest;

class FoodController extends Controller
{
    public function __construct(private FoodService $foodService)
    {
    }

    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $search = $request->query('search');

        return response()->json([
            'success' => true,
            'message' => 'Food list',
            'data' => $this->foodService->getAll($categoryId, $search),
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Food details',
            'data' => $this->foodService->getById($id),
        ]);
    }

    public function store(FoodCreateRequest $request)
    {
        $food = $this->foodService->create($request->all());
        if($request->hasFile('images')){
            $food->addMultipleMediaFromRequest(['images'])
            ->each(function ($fileAdder)
            {
                $fileAdder->toMediaCollection('images');
            });
        }
        return response()->json([
            'success' => true,
            'message' => 'Food created',
            'data' => $food->with('category')->get(),
        ]);
    }

    public function update(FoodUpdateRequest $request, $id)
    {
         $food = $this->foodService->getById($id);
        
        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food not found',
            ], 404);
        }

        $food = $this->foodService->update($id, $request->all());
        if($request->hasFile('images')){
            $food->addMultipleMediaFromRequest(['images'])
            ->each(function ($fileAdder)
            {
                $fileAdder->toMediaCollection('images');
            });
        }
        return response()->json([
            'success' => true,
            'message' => 'Food updated',
            'data' => $food,
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Food deleted',
            'data' => $this->foodService->delete($id),
        ]);
    }
}
