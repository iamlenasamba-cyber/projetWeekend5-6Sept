<?php

declare(strict_types=1);

use App\Model\Responsible;
use PHPUnit\Framework\TestCase;

final class ResponsibleAuthServiceTest extends TestCase
{
    public function testResponsibleCanBeBuiltFromReservationPayload(): void
    {
        $responsible = Responsible::fromReservation([
            'responsable' => 'Lena Samba',
            'email' => 'lena@example.com',
        ]);

        $this->assertSame('Lena Samba', $responsible->responsable);
        $this->assertSame('lena@example.com', $responsible->email);
    }

    public function testResponsibleMatchesResponsibleAndEmailPair(): void
    {
        $responsible = new Responsible('Lena Samba', 'lena@example.com');

        $this->assertTrue($responsible->matches('Lena Samba', 'lena@example.com'));
        $this->assertFalse($responsible->matches('Inconnu', 'unknown@example.com'));
    }
}
