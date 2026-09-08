<?php

namespace App\Exception;

final class ReservationNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Réservation introuvable");
    }
}
