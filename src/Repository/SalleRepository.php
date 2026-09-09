<?php

namespace App\Repository;

use App\Model\Salle;
use Illuminate\Database\Eloquent\Collection;
use \Illuminate\Database\Capsule\Manager;

final class SalleRepository implements SalleRepositoryInterface
{
    public function getAll(): Collection
    {
        return Salle::query()
            ->select('salles.*', 'types_salle.nom as type_nom')
            ->leftJoin('types_salle', 'types_salle.id', '=', 'salles.type_id')
            ->orderBy('salles.nom')
            ->get();
    }

    public function findById(int $id): ?Salle
    {
        return Salle::query()->find($id);
    }

    public function findTypeIdByName(string $name): ?int
    {
        $typeId = Manager::table('types_salle')
        ->where('nom', $name)
        ->value('id');

        return $typeId === null ? null : (int) $typeId;
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
