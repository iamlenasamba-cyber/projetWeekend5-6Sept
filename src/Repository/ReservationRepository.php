<?php

namespace App\Repository;

use App\Model\Reservation;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;

final class ReservationRepository implements ReservationRepositoryInterface
{
    public function getAll(): Collection
    {
        return Reservation::query()
            ->with('salle')
            ->orderBy('date_debut')
            ->get();
    }

    public function findById(int $id): ?Reservation
    {
        return Reservation::query()->with('salle')->find($id);
    }

    public function save(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function delete(Reservation $reservation): bool
    {
        return $reservation->delete();
    }

    public function findConflicts(int $salleId, DateTimeInterface $dateDebut, DateTimeInterface $dateFin): Collection
    {
        $start = $dateDebut->format('Y-m-d H:i:s');
        $end = $dateFin->format('Y-m-d H:i:s');

        return Reservation::query()
            ->where('salle_id', $salleId)
            ->where('date_debut', '<', $end)
            ->where('date_fin', '>', $start)
            ->get();
    }
}
