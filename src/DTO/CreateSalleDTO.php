<?php

namespace App\DTO;

use App\Validation\ValidatorInterface;

final readonly class CreateSalleDTO
{
    public string $nom;
    public string $batiment;
    public int $capacite;
    public int $typeId;
    public bool $active;

    public function __construct(
        string $nom,
        string $batiment,
        int $capacite,
        int $typeId,
        bool $active = true,
        ?ValidatorInterface $validator = null,
    ) {
        if ($validator !== null) {
            $result = $validator->validate([
                'nom' => $nom,
                'batiment' => $batiment,
                'capacite' => $capacite,
                'type_id' => $typeId,
                'active' => $active,
            ]);

            if (!$result->isValid()) {
                $messages = array_map(
                    static fn (array $errors): string => implode(' ', $errors),
                    $result->errors(),
                );

                throw new \InvalidArgumentException(implode(' ', $messages));
            }
        }

        $this->nom = $nom;
        $this->batiment = $batiment;
        $this->capacite = $capacite;
        $this->typeId = $typeId;
        $this->active = $active;
    }

    public static function fromArray(array $data, ?ValidatorInterface $validator = null): self
    {
        return new self(
            nom: (string) ($data['nom'] ?? ''),
            batiment: (string) ($data['batiment'] ?? ''),
            capacite: (int) ($data['capacite'] ?? 0),
            typeId: (int) ($data['type_id'] ?? $data['typeId'] ?? $data['type'] ?? 0),
            active: (bool) ($data['active'] ?? true),
            validator: $validator,
        );
    }
}
