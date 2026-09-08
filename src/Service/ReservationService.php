<?php

namespace App\Service;

use App\Exception\ReservationNotFoundException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class ReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
    ) {
    }

    public function getAll(): Collection
    {
        return $this->reservationRepository->getAll();
    }

    public function findById(int $id): Reservation
    {
        $reservation = $this->reservationRepository->findById($id);

        if ($reservation === null) {
            throw new ReservationNotFoundException();
        }

        return $reservation;
    }
}
