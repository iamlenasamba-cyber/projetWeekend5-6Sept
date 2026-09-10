<?php

declare(strict_types=1);

use App\Model\Reservation;
use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;

final class TestIntegrationEloquent extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        require dirname(__DIR__, 2) . '/config/database.php';
    }

    public function testCreationSalleAvecEloquentEtRelation(): void
    {
        Capsule::schema()->dropIfExists('reservations');
        Capsule::schema()->dropIfExists('salles');
        Capsule::schema()->dropIfExists('types_salle');

        Capsule::schema()->create('types_salle', function ($table): void {
            $table->id();
            $table->string('nom', 50)->unique();
        });

        Capsule::schema()->create('salles', function ($table): void {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->foreignId('type_id')->constrained('types_salle')->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

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

        $typeId = Capsule::table('types_salle')->insertGetId(['nom' => 'cours']);

        $salle = new Salle();
        $salle->nom = 'Dev Web';
        $salle->batiment = 'Bâtiment Informatique';
        $salle->capacite = 40;
        $salle->type_id = $typeId;
        $salle->active = true;
        $salle->save();

        $this->assertSame('Dev Web', $salle->nom);
        $this->assertCount(1, Salle::query()->get());
        $this->assertSame(1, $salle->reservations()->count());
    }

    public function testRelationSalleReservationsEtRechercheChevauchement(): void
    {
        Capsule::schema()->dropIfExists('reservations');
        Capsule::schema()->dropIfExists('salles');
        Capsule::schema()->dropIfExists('types_salle');

        Capsule::schema()->create('types_salle', function ($table): void {
            $table->id();
            $table->string('nom', 50)->unique();
        });

        Capsule::schema()->create('salles', function ($table): void {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->foreignId('type_id')->constrained('types_salle')->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

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

        $typeId = Capsule::table('types_salle')->insertGetId(['nom' => 'cours']);

        $salle = new Salle();
        $salle->nom = 'Dev Web';
        $salle->batiment = 'Bâtiment Informatique';
        $salle->capacite = 40;
        $salle->type_id = $typeId;
        $salle->active = true;
        $salle->save();

        $reservation = new Reservation();
        $reservation->salle_id = $salle->id;
        $reservation->responsable = 'Lena Samba';
        $reservation->email = 'lena@example.com';
        $reservation->motif = 'Réunion planning';
        $reservation->date_debut = '2026-09-10 09:00:00';
        $reservation->date_fin = '2026-09-10 11:00:00';
        $reservation->statut = 'confirmee';
        $reservation->save();

        $this->assertTrue($salle->reservations()->exists());
        $this->assertSame('Dev Web', $reservation->salle->nom);

        $conflicts = Reservation::query()
            ->where('salle_id', $salle->id)
            ->where('date_debut', '<', '2026-09-10 12:00:00')
            ->where('date_fin', '>', '2026-09-10 08:00:00')
            ->get();

        $this->assertCount(1, $conflicts);
    }

    public function testAnnulerReservationAvecSuppressionRepository(): void
    {
        Capsule::schema()->dropIfExists('reservations');
        Capsule::schema()->dropIfExists('salles');
        Capsule::schema()->dropIfExists('types_salle');

        Capsule::schema()->create('types_salle', function ($table): void {
            $table->id();
            $table->string('nom', 50)->unique();
        });

        Capsule::schema()->create('salles', function ($table): void {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->foreignId('type_id')->constrained('types_salle')->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

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

        $typeId = Capsule::table('types_salle')->insertGetId(['nom' => 'cours']);

        $salle = new Salle();
        $salle->nom = 'Dev Web';
        $salle->batiment = 'Bâtiment Informatique';
        $salle->capacite = 40;
        $salle->type_id = $typeId;
        $salle->active = true;
        $salle->save();

        $reservation = new Reservation();
        $reservation->salle_id = $salle->id;
        $reservation->responsable = 'Lena Samba';
        $reservation->email = 'lena@example.com';
        $reservation->motif = 'Réunion planning';
        $reservation->date_debut = '2026-09-10 09:00:00';
        $reservation->date_fin = '2026-09-10 11:00:00';
        $reservation->statut = 'confirmee';
        $reservation->save();

        $this->assertTrue($reservation->delete());
        $this->assertSame(0, Reservation::query()->count());
    }
}
