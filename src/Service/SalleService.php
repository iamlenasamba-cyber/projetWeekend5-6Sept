<?php

namespace App\Service;

use App\Exception\SalleNotFoundException;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class SalleService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository,
    ) {
    }

    public function getAll(): Collection
    {
        return $this->salleRepository->getAll();
    }

    public function findById(int $id): Salle
    {
        $salle = $this->salleRepository->findById($id);

        if ($salle === null) {
            throw new SalleNotFoundException();
        }

        return $salle;
    }
}
