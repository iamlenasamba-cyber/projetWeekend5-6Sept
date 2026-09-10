<?php

declare(strict_types=1);

use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

final class SalleValidatorTest extends TestCase
{
    public function testValidationSalleRefuseCapaciteNegative(): void
    {
        $validator = new SalleValidator();

        $result = $validator->validate([
            'nom' => 'Salle A',
            'batiment' => 'Bâtiment 1',
            'capacite' => -12,
            'type_id' => 1,
            'active' => true,
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('capacite', $result->errors());
    }

    public function testValidationSalleRefuseTypeInconnu(): void
    {
        $validator = new SalleValidator();

        $result = $validator->validate([
            'nom' => 'Salle A',
            'batiment' => 'Bâtiment 1',
            'capacite' => 10,
            'type_id' => 999,
            'active' => true,
        ]);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('type_id', $result->errors());
    }
}
