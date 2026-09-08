<?php

namespace App\Repository;

use App\Model\Reservation;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface
{
    public function getAll(): Collection;

    public function findById(int $id): ?Reservation;

    public function save(Reservation $reservation): Reservation;

    public function delete(Reservation $reservation): bool;

    public function findConflicts(int $salleId, DateTimeInterface $dateDebut, DateTimeInterface $dateFin): Collection;
}
