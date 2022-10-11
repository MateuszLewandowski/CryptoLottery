<?php 

namespace App\Web3\Validation;

final class WalletAddressValidation
{
    public static function check(string $address): bool 
    {
        if (! preg_match('/^(0x)?[0-9a-f]{40}$/i',$address)) {
            return false;
        } else if (! preg_match('/^(0x)?[0-9a-f]{40}$/', $address) || preg_match('/^(0x)?[0-9A-F]{40}$/', $address)) {
            return true;
        } 
        return self::isChecksumAddress($address);
    }

    private static function isChecksumAddress(string $address): bool 
    {
        $address = str_replace('0x','',$address);
        $address_hash = hash('sha3', strtolower($address));
        $address_arr = str_split($address);
        $address_hash_arr = str_split($address_hash);
        for ($i = 0; $i < 40; $i++ ) {
            if (
                (intval($address_hash_arr[$i], 16) > 7 && strtoupper($address_arr[$i]) !== $address_arr[$i]) || 
                (intval($address_hash_arr[$i], 16) <= 7 && strtolower($address_arr[$i]) !== $address_arr[$i])
            ) {
                return false;
            }
        }
        return true;
    }
}