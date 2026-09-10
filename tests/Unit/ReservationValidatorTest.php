<?php

declare(strict_types=1);

use App\Validation\ReservationValidator;
use PHPUnit\Framework\TestCase;

final class ReservationValidatorTest extends TestCase
{
    public function testValidationReservationRefuseEmailInvalide(): void
    {
        $validator = new ReservationValidator();

        $result = $validator->validate([
            'salle_id' => 1,
            'responsable' => 'Lena Samba',
            'email' => 'not-an-email',
            'motif' => 'Réunion planning',
            'date_debut' => '2026-09-09 09:00:00',
            'date_fin' => '2026-09-09 11:00:00',
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('email', $result->errors());
    }

    public function testValidationReservationAcceptePayloadValide(): void
    {
        $validator = new ReservationValidator();

        $result = $validator->validate([
            'salle_id' => 1,
            'responsable' => 'Lena Samba',
            'email' => 'Lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => '2026-09-09 09:00:00',
            'date_fin' => '2026-09-09 11:00:00',
        ]);

        $this->assertTrue($result->isValid());
        $this->assertSame('Lena@example.com', $result->acceptedData()['email']);
    }

    public function testValidationReservationRefuseDateInvalide(): void
    {
        $validator = new ReservationValidator();

        $result = $validator->validate([
            'salle_id' => 1,
            'responsable' => 'Lena Samba',
            'email' => 'Lena@example.com',
            'motif' => 'Réunion planning',
            'date_debut' => 'not-a-date',
            'date_fin' => '2026-09-09 11:00:00',
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('date_debut', $result->errors());
    }
}
