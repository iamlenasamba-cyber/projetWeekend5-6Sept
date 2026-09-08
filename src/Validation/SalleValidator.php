<?php

namespace App\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as RespectValidator;
use Illuminate\Database\Capsule\Manager as Capsule;

final class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $normalizedData = $data;

        if (!array_key_exists('type_id', $normalizedData) && array_key_exists('type', $normalizedData)) {
            $normalizedData['type_id'] = $normalizedData['type'];
        }

        return $this->validateFields($normalizedData, [
            'nom' => RespectValidator::stringType()->notEmpty()->length(2, 100),
            'batiment' => RespectValidator::stringType()->notEmpty()->length(2, 100),
            'capacite' => RespectValidator::intType()->between(1, 1000),
            'type_id' => RespectValidator::intType()->positive()->callback(
                static fn (int $typeId): bool => Capsule::table('types_salle')
                    ->where('id', $typeId)
                    ->exists()
            ),
            'active' => RespectValidator::boolType(),
        ]);
    }

    private function validateFields(array $data, array $rules): ValidationResult
    {
        $acceptedData = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            if (!array_key_exists($field, $data)) {
                $errors[$field] = ['Le champ est obligatoire.'];
                continue;
            }

            try {
                $rule->check($data[$field]);
                $acceptedData[$field] = $data[$field];
            } catch (ValidationException $exception) {
                $errors[$field] = [$exception->getMessage()];
            }
        }

        return new ValidationResult($acceptedData, $errors);
    }
}