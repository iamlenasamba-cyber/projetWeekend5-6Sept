<?php

use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions([
	SalleRepositoryInterface::class => DI\autowire(SalleRepository::class),
	ReservationRepositoryInterface::class => DI\autowire(ReservationRepository::class),
]);

return $builder->build();
