<?php

declare(strict_types=1);

use App\DTO\CreateReservationDTO;
use App\Exception\SalleNotFoundException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\CreateReservationService;
use App\Service\ReservationCreationContraintes;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

final class CreateReservationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Model::setConnectionResolver(new class implements ConnectionResolverInterface {
            public function connection($name = null)
            {
                return new class {
                    public function getQueryGrammar(): object
                    {
                        return new class {
                            public function getDateFormat(): string
                            {
                                return 'Y-m-d H:i:s';
                            }
                        };
                    }
                };
            }

            public function getDefaultConnection()
            {
                return 'default';
            }

            public function setDefaultConnection($name)
            {
            }
        });
    }

    public function testCreationReservationLanceQuandSalleInexistante(): void
    {
        $salleRepository = $this->createMock(SalleRepositoryInterface::class);
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);

        $salleRepository
            ->method('findById')
            ->with(42)
            ->willReturn(null);

        $service = new CreateReservationService($salleRepository, $reservationRepository, new ReservationCreationContraintes());

        $dto = CreateReservationDTO::fromArray([
            'salle_id' => 42,
            'responsable' => 'lena Samba',
            'email' => 'lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => '2026-09-09 09:00:00',
            'date_fin' => '2026-09-09 11:00:00',
        ]);

        $this->expectException(SalleNotFoundException::class);
        $service->execute($dto);
    }

    public function testCreationReservationLanceQuandSalleInactive(): void
    {
        $salleRepository = $this->createMock(SalleRepositoryInterface::class);
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);

        $salle = new Salle();
        $salle->id = 2;
        $salle->active = false;

        $salleRepository
            ->method('findById')
            ->with(2)
            ->willReturn($salle);

        $service = new CreateReservationService($salleRepository, $reservationRepository, new ReservationCreationContraintes());

        $dto = CreateReservationDTO::fromArray([
            'salle_id' => 2,
            'responsable' => 'lena Samba',
            'email' => 'lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => '2030-09-09 09:00:00',
            'date_fin' => '2030-09-09 11:00:00',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $service->execute($dto);
    }

    public function testCreationReservationLanceQuandDateFinAvantDateDebut(): void
    {
        $salleRepository = $this->createMock(SalleRepositoryInterface::class);
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);

        $salle = new Salle();
        $salle->id = 3;
        $salle->active = true;

        $salleRepository
            ->method('findById')
            ->with(3)
            ->willReturn($salle);

        $service = new CreateReservationService($salleRepository, $reservationRepository, new ReservationCreationContraintes());

        $dto = CreateReservationDTO::fromArray([
            'salle_id' => 3,
            'responsable' => 'lena Samba',
            'email' => 'lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => '2030-09-09 11:00:00',
            'date_fin' => '2030-09-09 09:00:00',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $service->execute($dto);
    }

    public function testCreationReservationLanceQuandDureeDepasseQuatreHeures(): void
    {
        $salleRepository = $this->createMock(SalleRepositoryInterface::class);
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);

        $salle = new Salle();
        $salle->id = 4;
        $salle->active = true;

        $salleRepository
            ->method('findById')
            ->with(4)
            ->willReturn($salle);

        $service = new CreateReservationService($salleRepository, $reservationRepository, new ReservationCreationContraintes());

        $dto = CreateReservationDTO::fromArray([
            'salle_id' => 4,
            'responsable' => 'Lena Samba',
            'email' => 'lena@gmail.com',
            'motif' => 'Réunion',
            'date_debut' => '2030-09-09 09:00:00',
            'date_fin' => '2030-09-09 14:00:00',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $service->execute($dto);
    }

    public function testCreationReservationLanceQuandReservationDansLePasse(): void
    {
        $salleRepository = $this->createMock(SalleRepositoryInterface::class);
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);

        $salle = new Salle();
        $salle->id = 5;
        $salle->active = true;

        $salleRepository
            ->method('findById')
            ->with(5)
            ->willReturn($salle);

        $service = new CreateReservationService($salleRepository, $reservationRepository, new ReservationCreationContraintes());

        $dto = CreateReservationDTO::fromArray([
            'salle_id' => 5,
            'responsable' => 'lena Samba',
            'email' => 'lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => '2020-09-09 09:00:00',
            'date_fin' => '2020-09-09 11:00:00',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $service->execute($dto);
    }

    public function testCreationReservationLanceQuandIlYALienConflit(): void
    {
        $salleRepository = $this->createMock(SalleRepositoryInterface::class);
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);

        $salle = new Salle();
        $salle->id = 6;
        $salle->active = true;

        $salleRepository
            ->method('findById')
            ->with(6)
            ->willReturn($salle);

        $reservationRepository
            ->method('findConflicts')
            ->willReturn(new Collection([
                new Reservation(),
            ]));

        $service = new CreateReservationService($salleRepository, $reservationRepository, new ReservationCreationContraintes());

        $dto = CreateReservationDTO::fromArray([
            'salle_id' => 6,
            'responsable' => 'lena Samba',
            'email' => 'lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => '2030-09-09 09:00:00',
            'date_fin' => '2030-09-09 11:00:00',
        ]);

        $this->expectException(\RuntimeException::class);
        $service->execute($dto);
    }

    public function testCreationReservationPersistQuandPayloadValide(): void
    {
        $salleRepository = $this->createMock(SalleRepositoryInterface::class);
        $reservationRepository = $this->createMock(ReservationRepositoryInterface::class);

        $salle = new Salle();
        $salle->id = 7;
        $salle->active = true;

        $salleRepository
            ->method('findById')
            ->with(7)
            ->willReturn($salle);

        $reservationRepository
            ->method('findConflicts')
            ->willReturn(new Collection());

        $reservation = new Reservation();
        $reservation->salle_id = 7;
        $reservation->responsable = 'lena Samba';
        $reservation->email = 'lena@example.com';
        $reservation->motif = 'Réunion planning';
        $reservation->date_debut = new \DateTimeImmutable('2030-09-09 09:00:00');
        $reservation->date_fin = new \DateTimeImmutable('2030-09-09 11:00:00');

        $reservationRepository
            ->method('save')
            ->willReturn($reservation);

        $service = new CreateReservationService($salleRepository, $reservationRepository, new ReservationCreationContraintes());

        $dto = CreateReservationDTO::fromArray([
            'salle_id' => 7,
            'responsable' => 'lena Samba',
            'email' => 'lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => '2030-09-09 09:00:00',
            'date_fin' => '2030-09-09 11:00:00',
        ]);

        $result = $service->execute($dto);

        $this->assertSame($reservation, $result);
    }
}
