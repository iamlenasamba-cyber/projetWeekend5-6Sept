<?php

namespace App\Exception;

final class SalleNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Salle introuvable");
    }
}
