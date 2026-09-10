<?php

namespace App\Controller;

use App\DTO\CreateReservationDTO;
use App\Repository\SalleRepositoryInterface;
use App\Repository\ReservationRepositoryInterface;
use App\Service\CreateReservationService;
use App\Service\ReservationService;
use App\Service\AnnulerReservationService;
use App\Exception\ReservationNotFoundException;
use App\Validation\ReservationValidator;
use App\View\ViewRenderer;

final class ReservationController
{
    public function __construct(
        private readonly ReservationService $reservationService,
        private readonly CreateReservationService $createReservationService,
        private readonly AnnulerReservationService $annulerReservationService,
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly ReservationValidator $reservationValidator,
        private readonly ViewRenderer $viewRenderer,
    ) {
    }

    public function index(): void
    {
        $reservations = $this->reservationService->getAll();

        echo $this->viewRenderer->render('reservation/index.php', [
            'reservations' => $reservations,
        ]);
    }

    public function create(): void
    {
        $salles = $this->salleRepository->getAll();

        echo $this->viewRenderer->render('reservation/create.php', [
            'salles' => $salles,
        ]);
    }

    public function show(int $id): void
    {
        try {
            $reservation = $this->reservationService->findById($id);

            echo $this->viewRenderer->render('reservation/show.php', [
                'reservation' => $reservation,
            ]);
        } catch (ReservationNotFoundException $exception) {
            http_response_code(404);
            echo $this->viewRenderer->render('error/404.php');
        }
    }

    public function store(): void
    {
        try {
            $dto = CreateReservationDTO::fromArray($_POST, $this->reservationValidator);
            $this->createReservationService->execute($dto);
            header('Location: /reservations');
            exit;
        } catch (\InvalidArgumentException | \RuntimeException $exception) {
            $error = $exception->getMessage();
            $salles = $this->salleRepository->getAll();

            echo $this->viewRenderer->render('reservation/create.php', [
                'error' => $error,
                'salles' => $salles,
            ]);
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
            echo $this->viewRenderer->render('error/404.php');
        }
    }
} 
