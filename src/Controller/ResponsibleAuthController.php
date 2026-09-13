<?php


namespace App\Controller;

use App\Repository\ReservationRepository;
use App\View\ViewRenderer;

final class ResponsibleAuthController
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly ViewRenderer $viewRenderer,
    ) {
    }

    public function index(): void
    {
        echo $this->viewRenderer->render('responsable/login.php');
    }

    public function authenticate(): void
    {
        $responsable = trim((string) ($_POST['responsable'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));

        $reservations = $this->reservationRepository->getAll();
        $trouve = false;

        foreach ($reservations as $reservation) {
            if ($this->sameResponsible($responsable, $reservation->responsable)
                && $this->memeEmail($email, $reservation->email)) {
                $trouve = true;
                break;
            }
        }

        if ($trouve) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['responsable'] = $responsable;
            $_SESSION['responsable_email'] = $email;
            $_SESSION['responsable_authenticated'] = true;

            header('Location: /reservations');
            exit;
        }

        echo $this->viewRenderer->render('responsable/login.php', [
            'error' => 'Responsable ou email invalide.',
        ]);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['responsable']);
        unset($_SESSION['responsable_email']);
        unset($_SESSION['responsable_authenticated']);
        session_destroy();

        header('Location: /responsable/login');
        exit;
    }

    private function sameResponsible(string $input, string $stored): bool
    {
        $inputNormalized = strtolower(trim(preg_replace('/\s+/', ' ', $input)));
        $storedNormalized = strtolower(trim(preg_replace('/\s+/', ' ', $stored)));

        return $inputNormalized !== ''
            && $inputNormalized === $storedNormalized;
    }

    private function memeEmail(string $input, string $stored): bool
    {
        $inputParts = explode('@', strtolower(trim($input)), 2);
        $storedParts = explode('@', strtolower(trim($stored)), 2);

        return isset($inputParts[0], $storedParts[0])
            && $inputParts[0] !== ''
            && $inputParts[0] === $storedParts[0];
    }
}
