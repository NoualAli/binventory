<?php

namespace App\Enums;

enum EquipmentStateEnum:string
{
    case PENDING_ASSIGNATION = "En attente d'assignation";
    case ASSIGNED = "En attente de traitement";
    case IN_PROGRESS = "En cours";
    case RESOLVED = "Problème résolu";
    case TO_REFORMED = "À réformer";

    public static function asArray(): array {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->value; // Set key and value
            return $carry;
        }, []);
    }
}

