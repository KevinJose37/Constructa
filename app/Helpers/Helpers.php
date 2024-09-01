<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helpers
{
    protected static $ivaRate = null;

    protected static function formatAssignIVA($ivaRate = null)
    {
        if ($ivaRate === null || empty($ivaRate)) {
            return self::$ivaRate = DB::table('settings')->where('key', 'iva')->first()->value;
        }
        self::$ivaRate = $ivaRate / 100;
    }

    public static function calculateIva($amount, $iva = null)
    {
        self::formatAssignIVA($iva);
        return bcmul($amount, self::$ivaRate, 2); // Multiplica la cantidad por la tasa de IVA con precisión de 2 decimales

    }


    public static function calculateTotalIva($amount, $iva = null)
    {
        if ($iva == 0 || !$iva) {
            return $amount;
        }
        self::formatAssignIVA($iva);
        $ivaRatePlusOne = bcadd(self::$ivaRate, '1', 2); // Suma 1 a la tasa de IVA con precisión de 2 decimales
        return bcmul($amount, $ivaRatePlusOne, 2); // Multiplica la cantidad por (1 + tasa de IVA) con precisión de 2 decimales
    }
}
