<?php 
namespace App\Helpers;

class Luhn {
    public static function generateAccountNumber($length = 12) {
        do {
            $number = substr(str_shuffle("01234567891"), 0, $length - 1);
            //dd($number);
            $checksum = self::calculateChecksum($number);
            //dd($checksum);
            $accountNumber = $number . $checksum;
        } while (\App\Models\Account::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }

    public static function validate($number) {
        $sum = 0;
        $reverse = strrev($number);
        foreach (str_split($reverse) as $i => $digit) {
            $num = (int)$digit;
            if ($i % 2 == 0) {
                $num *= 2;
                if ($num > 9) $num -= 9;
            }
            $sum += $num;
        }
        return ($sum % 10) === 0;
    }

    private static function calculateChecksum($number) {
        $sum = 0;
        $reverse = strrev($number);
        foreach (str_split($reverse) as $i => $digit) {
            $num = (int)$digit;
            if ($i % 2 === 0) {
                $num *= 2;
                if ($num > 9) $num -= 9;
            }
            $sum += $num;
        }
        return (10 - ($sum % 10)) % 10;
    }
}
