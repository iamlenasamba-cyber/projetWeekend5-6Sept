<?php

namespace App\Controller;

use App\DTO\CreateSalleDTO;
use App\Exception\SalleNotFoundException;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Model\Salle;
use App\Service\SalleService;

final class SalleController
{
    public function __construct(
        private readonly SalleService $salleService,
        private readonly SalleRepositoryInterface $salleRepository,
    ) {
    }

    public function index(): void
    {
        $salles = $this->salleService->getAll();

        require_once dirname(__DIR__,2) . '/templates/salle/index.php';
    }

    public function create(): void
    {
        require_once dirname(__DIR__, 2) . '/templates/salle/create.php';
    }

    public function show(int $id): void
    {
        try {
            $salle = $this->salleService->findById($id);
            require_once dirname(__DIR__, 2) . '/templates/salle/show.php';
            
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            require_once dirname(__DIR__, 2) . '/templates/error/404.php';
        }
    }

    public function edit(int $id): void
    {
        try {
            $salle = $this->salleService->findById($id);
            require_once dirname(__DIR__, 2) . '/templates/salle/create.php';
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            require_once dirname(__DIR__, 2) . '/templates/error/404.php';
        }
    }

    public function update(int $id): void
    {
        try {
            $dto = new CreateSalleDTO(
                nom: $_POST['nom'] ?? '',
                batiment: $_POST['batiment'] ?? '',
                capacite: $_POST['capacite'] ?? 0,
                typeId: $_POST['type_id'] ?? 0,
                active: isset($_POST['active']) ? (bool) $_POST['active'] : true,
            );
            $salle = $this->salleService->findById($id);
            $salle->fill([
                'nom' => $dto->nom,
                'batiment' => $dto->batiment,
                'capacite' => $dto->capacite,
                'type_id' => $dto->typeId,
                'active' => $dto->active,
            ]);
            $this->salleRepository->save($salle);
            header('Location: /salles/' . $id);
            exit;
        } catch (\InvalidArgumentException $exception) {
            $error = $exception->getMessage();
            require_once dirname(__DIR__, 2) . '/templates/salle/create.php';
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            require_once dirname(__DIR__, 2) . '/templates/error/404.php';
        }
    }

    public function store(): void
    {
        try {
            $dto = new CreateSalleDTO(
                nom:  $_POST['nom'],
                batiment: $_POST['batiment'] ,
                capacite: $_POST['capacite'] ,
                typeId:  $_POST['type_id'] ,
                active: isset($_POST['active']) ? (bool) $_POST['active'] : true,
            );

            $salle = new Salle();
            $salle->nom = $dto->nom;
            $salle->batiment = $dto->batiment;
            $salle->capacite = $dto->capacite;
            $salle->type_id = $dto->typeId;
            $salle->active = $dto->active;
            $this->salleRepository->save($salle);

            header('Location: /salles');
            exit;
        } catch (\InvalidArgumentException $exception) {
            $error = $exception->getMessage();
            require_once dirname(__DIR__,2) . '/templates/salle/create.php';
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            echo $exception->getMessage();
        }
    }
}
