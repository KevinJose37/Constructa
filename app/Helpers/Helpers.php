<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helpers {
    protected static $ivaRate = null;

    protected static function loadIvaRate() {
        if (self::$ivaRate === null) {
            self::$ivaRate = DB::table('settings')->where('key', 'iva')->first()->value;
        }
    }

    public static function calculateIva($amount) {
        self::loadIvaRate();
        return $amount * self::$ivaRate;
    }

    
    public static function calculateTotalIva($amount) {
        self::loadIvaRate();
        $amount = str_replace(',', '', $amount);
        return (float)$amount * (self::$ivaRate + 1);
    }
}