<?php

namespace App\Controller;

use App\DTO\CreateReservationDTO;
use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Repository\ReservationRepositoryInterface;
use App\Service\CreateReservationService;
use App\Service\ReservationService;
use App\Service\AnnulerReservationService;
use App\Exception\ReservationNotFoundException;

final class ReservationController
{
    public function __construct(
        private readonly ReservationService $reservationService,
        private readonly CreateReservationService $createReservationService,
        private readonly AnnulerReservationService $annulerReservationService,
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly ReservationRepositoryInterface $reservationRepository,
    ) {
    }

    public function index(): void
    {
        $reservations = $this->reservationService->getAll();

        require_once dirname(__DIR__,2) . '/templates/reservation/index.php';
    }

    public function create(): void
    {
        $salles = $this->salleRepository->getAll();
        require_once dirname(__DIR__,2) . '/templates/reservation/create.php';
    }

    public function show(int $id): void
    {
        try {
            $reservation = $this->reservationService->findById($id);
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
            $this->createReservationService->execute($dto);
            header('Location: /reservations');
            exit;
        } catch (\InvalidArgumentException | \RuntimeException $exception) {
            $error = $exception->getMessage();
            $salles = $this->salleRepository->getAll();
            require_once dirname(__DIR__,2) . '/templates/reservation/create.php';
        }
    }

    public function cancel(int $id): void
    {
        try {
            $this->annulerReservationService->execute($id);
            header('Location: /reservations');
            exit;
        } catch (ReservationNotFoundException $exception) {
            http_response_code(404);
            require_once dirname(__DIR__, 2) . '/templates/error/404.php';
        }
    }
} 
