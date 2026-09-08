<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class ReservationMigration extends Migration
{
    public function up(): void
    {
        Capsule::schema()->create('reservations', function ($table): void {
            $table->id();
            $table->foreignId('salle_id')->constrained('salles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('responsable', 100);
            $table->string('email', 150);
            $table->string('motif', 255);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->enum('statut', ['confirmee', 'annulee'])->default('confirmee');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('reservations');
    }
}