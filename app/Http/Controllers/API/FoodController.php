<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\FoodService;
use App\Http\Controllers\Controller;
use App\Http\Requests\FoodCreateRequest;

class FoodController extends Controller
{
    public function __construct(private FoodService $foodService)
    {
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Food list',
            'data' => $this->foodService->getAll(),
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
            'data' => $food,
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Food updated',
            'data' => $this->foodService->update($id, $request->all()),
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
