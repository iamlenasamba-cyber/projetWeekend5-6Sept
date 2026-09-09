<?php

use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use Illuminate\Database\Capsule\Manager as CapsuleManager;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions([
	SalleRepositoryInterface::class => DI\autowire(SalleRepository::class),
	ReservationRepositoryInterface::class => DI\autowire(ReservationRepository::class),
	SalleValidator::class => DI\autowire(SalleValidator::class),
	ReservationValidator::class => DI\autowire(ReservationValidator::class),
	CapsuleManager::class => DI\factory(static function (): CapsuleManager {
		return require dirname(__DIR__) . '/config/database.php';
	}),
]);

return $builder->build();
