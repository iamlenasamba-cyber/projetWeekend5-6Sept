<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class TypeSalleMigration extends Migration
{
    public function up(): void
    {
        Capsule::schema()->create('types_salle', function ($table): void {
            $table->id();
            $table->string('nom', 50)->unique();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('types_salle');
    }
}