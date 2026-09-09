<?php

namespace App\Service;

use App\DTO\CreateReservationDTO;
use App\Exception\SalleNotFoundException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

final class CreateReservationService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly ReservationRepositoryInterface $reservationRepository,
    ) {
    }

    public function execute(CreateReservationDTO $dto): Reservation
    {
        $salle = $this->salleRepository->findById($dto->salleId);

        if ($salle === null) {
            throw new SalleNotFoundException();
        }

        $conflits = $this->reservationRepository->findConflicts(
            $dto->salleId,
            $dto->dateDebut,
            $dto->dateFin,
        );

        if ($conflits->isNotEmpty()) {
            throw new \RuntimeException('La salle est déjà réservée sur cette période.');
        }

        $reservation = new Reservation();
        $reservation->salle_id = $dto->salleId;
        $reservation->responsable = $dto->responsable;
        $reservation->email = $dto->email;
        $reservation->motif = $dto->motif;
        $reservation->date_debut = $dto->dateDebut;
        $reservation->date_fin = $dto->dateFin;

        return $this->reservationRepository->save($reservation);
    }
}
