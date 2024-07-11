<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helpers {
    protected static $ivaRate = null;

    protected static function formatAssignIVA($ivaRate = null) {
        if ($ivaRate === null || empty($ivaRate)) {
            return self::$ivaRate = DB::table('settings')->where('key', 'iva')->first()->value;
        }
        self::$ivaRate = $ivaRate / 100;
    }

    public static function calculateIva($amount, $iva = null) {
        self::formatAssignIVA($iva);
        return round($amount * self::$ivaRate, 1);
    }
    
    public static function calculateTotalIva($amount, $iva = null) {
        if($iva == 0){
            return $amount;
        }
        self::formatAssignIVA($iva);
        $amount = str_replace(',', '.', str_replace('.', '', $amount));
        return round((float)$amount * (self::$ivaRate + 1), 1);
    }
}