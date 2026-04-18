<?php

namespace App\Services;

use App\Models\Procedure;

class ProcedureService
{
    public function getAll()
    {
        return Procedure::orderBy('name')->get();
    }

    public function getActive()
    {
        return Procedure::where('is_active', true)->orderBy('name')->get();
    }

    public function create(array $data): Procedure
    {
        return Procedure::create([
            'name'      => $data['name'],
            'is_active' => true,
        ]);
    }

    public function update(Procedure $procedure, array $data): Procedure
    {
        $procedure->update(['name' => $data['name']]);
        return $procedure->fresh();
    }

    public function toggle(Procedure $procedure): Procedure
    {
        $procedure->update(['is_active' => !$procedure->is_active]);
        return $procedure->fresh();
    }

    public function delete(Procedure $procedure): void
    {
        $procedure->delete();
    }
}
