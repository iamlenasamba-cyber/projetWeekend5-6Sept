<?php

declare(strict_types=1);

use App\Exception\ReservationNotFoundException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Service\AnnulerReservationService;
use PHPUnit\Framework\TestCase;

final class AnnulerReservationServiceTest extends TestCase
{
    public function testAnnulationReservationLanceQuandReservationInexistante(): void
    {
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);
        $reservationRepository
            ->method('findById')
            ->with(42)
            ->willReturn(null);

        $service = new AnnulerReservationService($reservationRepository);

        $this->expectException(ReservationNotFoundException::class);
        $service->execute(42);
    }

    public function testAnnulationReservationSupprimeQuandReservationExiste(): void
    {
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);
        $reservation = new Reservation();

        $reservationRepository->method('findById')->with(7)->willReturn($reservation);

        $reservationRepository
            ->expects($this->once())
            ->method('delete')
            ->with($reservation)
            ->willReturn(true);

        $service = new AnnulerReservationService($reservationRepository);

        $this->assertTrue($service->execute(7));
    }
}
