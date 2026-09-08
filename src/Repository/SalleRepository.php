<?php

namespace App\Repository;

use App\Model\Salle;
use Illuminate\Database\Eloquent\Collection;

final class SalleRepository implements SalleRepositoryInterface
{
    public function getAll(): Collection
    {
        return Salle::query()
            ->orderBy('nom')
            ->get();
    }

    public function findById(int $id): ?Salle
    {
        return Salle::query()->find($id);
    }

    public function save(Salle $salle): Salle
    {
        $salle->save();

        return $salle;
    }

    public function delete(Salle $salle): bool
    {
        return $salle->delete();
    }
}
