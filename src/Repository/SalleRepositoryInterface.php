<?php

namespace App\Repository;

use App\Model\Salle;
use Illuminate\Database\Eloquent\Collection;

interface SalleRepositoryInterface
{
    public function getAll(): Collection;

    public function findById(int $id): ?Salle;

    public function save(Salle $salle): Salle;

    public function delete(Salle $salle): bool;
}
