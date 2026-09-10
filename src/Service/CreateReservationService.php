<?php

namespace App\Service;

use App\DTO\CreateReservationDTO;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

final class CreateReservationService
{
    public function __construct(
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly ReservationCreationContraintes $ReservationCreationContraintes,
    ) {
    }

    public function execute(CreateReservationDTO $dto): Reservation
    {
        $salle = $this->salleRepository->findById($dto->salleId);

        $this->ReservationCreationContraintes->verifier(
            $salle,
            $dto,
            $this->reservationRepository,
        );

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
