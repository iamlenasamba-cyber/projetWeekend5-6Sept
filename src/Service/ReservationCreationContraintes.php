<?php

namespace App\Service;

use App\DTO\CreateReservationDTO;
use App\Exception\SalleNotFoundException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;

class ReservationCreationContraintes
{
    public function __construct()
    {
    }

    public function verifier(?Salle $salle, CreateReservationDTO $dto, ReservationRepositoryInterface $reservationRepository): void
    {
        $this->verifierSalleExiste($salle);
        $this->verifierSalleActive($salle);
        $this->verifierChronologie($dto);
        $this->verifierDureeMaxQuatreHeures($dto);
        $this->verifierReservationDansLeFutur($dto);
        $this->verifierConflit($reservationRepository, $dto);
    }

    private function verifierSalleExiste(?Salle $salle): void
    {
        if ($salle === null) {
            throw new SalleNotFoundException();
        }
    }

    private function verifierSalleActive(Salle $salle): void
    {
        if (!$salle->active) {
            throw new \InvalidArgumentException('La salle est inactive.');
        }
    }

    private function verifierChronologie(CreateReservationDTO $dto): void
    {
        if ($dto->dateFin < $dto->dateDebut) {
            throw new \InvalidArgumentException('La date de fin doit être postérieure à la date de début.');
        }
    }

    private function verifierDureeMaxQuatreHeures(CreateReservationDTO $dto): void
    {
        $durationSeconds = $dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp();

        if ($durationSeconds > 4 * 3600) {
            throw new \InvalidArgumentException('La réservation ne peut pas dépasser 4 heures.');
        }
    }

    private function verifierReservationDansLeFutur(CreateReservationDTO $dto): void
    {
        $now = new \DateTimeImmutable('now');

        if ($dto->dateDebut < $now) {
            throw new \InvalidArgumentException('La réservation ne peut pas être créée dans le passé.');
        }
    }

    private function verifierConflit(ReservationRepositoryInterface $reservationRepository, CreateReservationDTO $dto): void
    {
        $conflicts = $reservationRepository->findConflicts(
            $dto->salleId,
            $dto->dateDebut,
            $dto->dateFin,
        );

        if ($conflicts->isNotEmpty()) {
            throw new \RuntimeException('La salle est déjà réservée sur cette période.');
        }
    }
}
