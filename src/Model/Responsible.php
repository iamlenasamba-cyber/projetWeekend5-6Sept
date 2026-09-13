<?php

declare(strict_types=1);

namespace App\Model;

final class Responsible
{
    public function __construct(
        public readonly string $responsable,
        public readonly string $email,
    ) {
    }

    public static function fromReservation(array $data): self
    {
        return new self(
            responsable: (string) ($data['responsable'] ?? ''),
            email: (string) ($data['email'] ?? ''),
        );
    }

    public function matches(string $responsable, string $email): bool
    {
        return $this->responsable === $responsable
            && $this->email === $email;
    }
}
