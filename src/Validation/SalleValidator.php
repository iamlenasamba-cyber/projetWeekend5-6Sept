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
                $fieldName = match ($field) {
                    'nom' => 'nom',
                    'batiment' => 'bâtiment',
                    'capacite' => 'capacité',
                    'type_id' => 'type',
                    'active' => 'activité',
                    default => $field,
                };

                $message = $exception->getMessage();
                if (str_contains($message, 'must be positive')) {
                    $errors[$field] = ["Veuillez saisir un type valide."];
                } elseif (str_contains($message, 'must be greater than')) {
                    $errors[$field] = ["La capacité doit être supérieure à 0."];
                } elseif (str_contains($message, 'must be a string')) {
                    $errors[$field] = ["Le champ {$fieldName} doit être une chaîne de caractères."];
                } elseif (str_contains($message, 'must have a length')) {
                    $errors[$field] = ["Le champ {$fieldName} doit contenir entre 2 et 100 caractères."];
                } elseif (str_contains($message, 'must be a valid date')) {
                    $errors[$field] = ["Le champ {$fieldName} doit être une date valide."];
                } else {
                    $errors[$field] = ["Le champ {$fieldName} n’est pas valide."];
                }
            }
        }

        return new ValidationResult($acceptedData, $errors);
    }
}