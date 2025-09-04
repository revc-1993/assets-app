<?php

namespace App\Enums;

/**
 * Define las constantes para los tipos de transacción.
 * Esto centraliza los IDs y evita el "hardcoding" en el código.
 */
class TransactionTypeConstants
{
    /**
     * ID para el tipo de transacción 'Ingreso'.
     */
    const TYPE_INCOME = 1;

    /**
     * ID para el tipo de transacción 'Ajuste'.
     */
    const TYPE_ADJUSTMENT = 2;

    /**
     * ID para el tipo de transacción 'Encargo'.
     */
    const TYPE_ASSIGNMENT = 3;

    /**
     * ID para el tipo de transacción 'Descargo'.
     */
    const TYPE_DISCHARGE = 4;

    /**
     * ID para el tipo de transacción 'Cambio de ubicación'.
     */
    const TYPE_LOCATION_CHANGE = 5;
}
