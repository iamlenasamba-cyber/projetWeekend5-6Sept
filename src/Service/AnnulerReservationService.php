<?php

namespace App\Service;

use App\Exception\ReservationNotFoundException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

final class AnnulerReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
    ) {}

    public function execute(int $reservationId): bool
    {
        $reservation = $this->reservationRepository->findById($reservationId);

        if ($reservation === null) {
            throw new ReservationNotFoundException();
        }

        return $this->reservationRepository->delete($reservation);
    }
}
