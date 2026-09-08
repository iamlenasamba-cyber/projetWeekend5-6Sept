<?php

namespace App\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as RespectValidator;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $normalizedData = $data;

        $aliases = [
            'salleId' => 'salle_id',
            'dateDebut' => 'date_debut',
            'dateFin' => 'date_fin',
        ];

        foreach ($aliases as $source => $target) {
            if (!array_key_exists($target, $normalizedData) && array_key_exists($source, $normalizedData)) {
                $normalizedData[$target] = $normalizedData[$source];
            }
        }

        $rules = [
            'salle_id' => RespectValidator::intType()->positive(),
            'responsable' => RespectValidator::stringType()->notEmpty()->length(2, 120),
            'email' => RespectValidator::email(),
            'motif' => RespectValidator::stringType()->notEmpty()->length(5, 255),
            'date_debut' => RespectValidator::dateTime(),
            'date_fin' => RespectValidator::dateTime(),
        ];
        $acceptedData = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            if (!array_key_exists($field, $normalizedData)) {
                $errors[$field] = ['Le champ est obligatoire.'];
                continue;
            }

            try {
                $rule->check($normalizedData[$field]);
                $acceptedData[$field] = $normalizedData[$field];
            } catch (ValidationException $exception) {
                $errors[$field] = [$exception->getMessage()];
            }
        }

        return new ValidationResult($acceptedData, $errors);
    }
}