<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\TableService;
use App\Http\Controllers\Controller;

class TableController extends Controller
{
    public function __construct(private TableService $tableService)
    {
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Table list',
            'data' => $this->tableService->getAll(),
            'statistics' => $this->tableService->getStatistics(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved,inactive',
        ]);

        $table = $this->tableService->update($id, $request->all());

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Table updated',
            'data' => $table,
        ]);
    }
}
