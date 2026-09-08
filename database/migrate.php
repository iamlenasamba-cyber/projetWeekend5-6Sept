<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = require_once dirname(__DIR__) . '/config/database.php';
$schema = $capsule->schema();
$migrationPath = __DIR__ . '/migrations';

if (($argv[1] ?? 'migrate') === 'fresh') {
    $schema->disableForeignKeyConstraints();
    foreach (['reservations', 'salles', 'types_salle', 'migrations'] as $table) {
        $schema->dropIfExists($table);
    }
    $schema->enableForeignKeyConstraints();
}

if (!$schema->hasTable('migrations')) {
    $schema->create('migrations', function ($table): void {
        $table->id();
        $table->string('migration');
        $table->unsignedInteger('batch');
    });
}

$completed = Capsule::table('migrations')->pluck('migration')->all();
$batch = (int) (Capsule::table('migrations')->max('batch') ?? 0) + 1;
$files = [
    $migrationPath . '/TypeSalleMigration.php',
    $migrationPath . '/SalleMigration.php',
    $migrationPath . '/ReservationMigration.php',
];

foreach ($files as $file) {
    $name = pathinfo($file, PATHINFO_FILENAME);

    if (in_array($name, $completed, true)) {
        continue;
    }

    require_once $file;
    $migration = new $name();
    $migration->up();
    Capsule::table('migrations')->insert(['migration' => $name, 'batch' => $batch]);
    echo "Migration exécutée : {$name}\n";
}

echo "Migrations terminées.\n";