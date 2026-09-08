<?php

namespace App\DTO;


final readonly class CreateReservationDTO
{
    private function __construct(
        public int $salleId,
        public string $responsable,
        public string $email,
        public string $motif,
        public \DateTimeImmutable $dateDebut,
        public \DateTimeImmutable $dateFin,
    ) {
        $validator = new \App\Validation\ReservationValidator();
        $result = $validator->validate([
            'salle_id' => $salleId,
            'responsable' => $responsable,
            'email' => $email,
            'motif' => $motif,
            'date_debut' => $dateDebut->format('Y-m-d H:i:s'),
            'date_fin' => $dateFin->format('Y-m-d H:i:s'),
        ]);

        if (! $result->isValid()) {
            throw new \InvalidArgumentException($result->errors());
        }
    }

    public static function fromArray(array $data): self
    {
        return new self(
            salleId:$data['salle_id'] ,
            responsable: $data['responsable'] ,
            email:  $data['email'] ,
            motif: $data['motif'] ,
            dateDebut: $data['date_debut'] ,
            dateFin: $data['date_fin'] ,
        );
    }

}
