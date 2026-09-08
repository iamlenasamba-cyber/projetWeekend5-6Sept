<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SalleMigration extends Migration
{
    public function up(): void
    {
        Capsule::schema()->create('salles', function ($table): void {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->foreignId('type_id')->constrained('types_salle')->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Capsule::schema()->dropIfExists('salles');
    }
}