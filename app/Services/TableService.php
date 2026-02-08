<?php

namespace App\Services;

use App\Models\Table;

final class TableService
{
    public function getAll()
    {
        return Table::all();
    }

    public function getStatistics()
    {
        $stats = Table::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'available' => $stats['available'] ?? 0,
            'occupied' => $stats['occupied'] ?? 0,
            'reserved' => $stats['reserved'] ?? 0,
            'inactive' => $stats['inactive'] ?? 0,
        ];
    }

    public function update($id, $data)
    {
        $table = Table::find($id);
        
        if (!$table) {
            return null;
        }

        $table->fill($data);

        if ($table->isDirty()) {
            $table->save();
        }
        
        return $table;
    }
}
