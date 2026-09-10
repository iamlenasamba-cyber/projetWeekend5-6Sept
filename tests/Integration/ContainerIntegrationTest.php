<?php


use App\Service\AnnulerReservationService;
use App\Service\CreateReservationService;
use App\Service\ReservationCreationContraintes;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;
use  \Psr\Container\ContainerInterface;
final class ContainerIntegrationTest extends TestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = require dirname(__DIR__, 2) . '/config/container.php';
    }

    public function testConteneurConstruitServicesEtValidateursCreationAnnulation(): void
    {
        $this->assertInstanceOf(CreateReservationService::class, $this->container->get(CreateReservationService::class));
        $this->assertInstanceOf(AnnulerReservationService::class, $this->container->get(AnnulerReservationService::class));
        $this->assertInstanceOf(ReservationCreationContraintes::class, $this->container->get(ReservationCreationContraintes::class));
        $this->assertInstanceOf(ReservationValidator::class, $this->container->get(ReservationValidator::class));
        $this->assertInstanceOf(SalleValidator::class, $this->container->get(SalleValidator::class));
    }
}
