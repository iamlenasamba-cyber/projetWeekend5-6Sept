<?php

namespace App\Controller;

use App\DTO\CreateSalleDTO;
use App\Exception\SalleNotFoundException;
use App\Repository\SalleRepositoryInterface;
use App\Model\Salle;
use App\Service\SalleService;
use App\Validation\SalleValidator;
use App\View\ViewRenderer;

final class SalleController
{
    public function __construct(
        private readonly SalleService $salleService,
        private readonly SalleRepositoryInterface $salleRepository,
        private readonly SalleValidator $salleValidator,
        private readonly ViewRenderer $viewRenderer,
    ) {
    }

    public function index(): void
    {
        $salles = $this->salleService->getAll();

        echo $this->viewRenderer->render('salle/index.php', [
            'salles' => $salles,
        ]);
    }

    public function create(): void
    {
        echo $this->viewRenderer->render('salle/create.php');
    }

    public function show(int $id): void
    {
        try {
            $salle = $this->salleService->findById($id);

            echo $this->viewRenderer->render('salle/show.php', [
                'salle' => $salle,
            ]);
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            echo $this->viewRenderer->render('error/404.php');
        }
    }

    public function edit(int $id): void
    {
        try {
            $salle = $this->salleService->findById($id);

            echo $this->viewRenderer->render('salle/create.php', [
                'salle' => $salle,
            ]);
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            echo $this->viewRenderer->render('error/404.php');
        }
    }

    public function update(int $id): void
    {
        try {
            $data = $_POST;
            $typeId = $this->salleRepository->findTypeIdByName((string) ($data['type'] ?? ''));
            $data['type_id'] = $typeId ?? 0;
            $dto = CreateSalleDTO::fromArray($data, $this->salleValidator);
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
            echo $this->viewRenderer->render('salle/create.php', [
                'error' => $error,
            ]);
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            echo $this->viewRenderer->render('error/404.php');
        }
    }

    public function store(): void
    {
        try {
            $data = $_POST;
            $typeId = $this->salleRepository->findTypeIdByName((string) ($data['type'] ?? ''));
            $data['type_id'] = $typeId ?? 0;
            $dto = CreateSalleDTO::fromArray($data, $this->salleValidator);

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
            echo $this->viewRenderer->render('salle/create.php', [
                'error' => $error,
            ]);
        } catch (SalleNotFoundException $exception) {
            http_response_code(404);
            echo $this->viewRenderer->render('error/404.php');
        }
    }
}
