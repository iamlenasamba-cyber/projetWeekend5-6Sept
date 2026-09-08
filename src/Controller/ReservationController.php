<?php

namespace App\Controller;

use App\DTO\CreateReservationDTO;
use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;
use App\Service\CreateReservationService;
use App\Service\ReservationService;
use App\Exception\ReservationNotFoundException;

final class ReservationController
{
    public function index(): void
    {
        $service = new ReservationService(new ReservationRepository());
        $reservations = $service->getAll();

        require_once dirname(__DIR__,2) . '/templates/reservation/index.php';
    }

    public function create(): void
    {
        $salles = (new SalleRepository())->getAll();
        require_once dirname(__DIR__,2) . '/templates/reservation/create.php';
    }

    public function show(int $id): void
    {
        try {
            $reservation = (new ReservationService(new ReservationRepository()))->findById($id);
            require_once dirname(__DIR__,2) . '/templates/reservation/show.php';
        } catch (ReservationNotFoundException $exception) {
            http_response_code(404);
            require_once dirname(__DIR__,2) . '/templates/error/404.php';
        }
    }

    public function store(): void
    {
        try {
            $dto = CreateReservationDTO::fromArray($_POST);
            $service = new CreateReservationService(new SalleRepository(), new ReservationRepository());
            $service->execute($dto);
            header('Location: /reservations');
            exit;
        } catch (\InvalidArgumentException | \RuntimeException $exception) {
            $error = $exception->getMessage();
            $salles = (new SalleRepository())->getAll();
            require_once dirname(__DIR__,2) . '/templates/reservation/create.php';
        }
    }
} 
