<?php 

namespace App\Trait;

use Symfony\Component\HttpFoundation\Request;

trait RequestAPITokenValidationTrait 
{
    /**
     * ecc30c2bc95ff372e91c31b2553df8c3e45dc1a493d3899f2e530676831f8fbc662628bf799e94eaa12baac04b7309d93f866d7cd3576239c8cf1011532f4762
     */
    private static function validateAPIToken(string|bool $api_token): bool
    {
        if ($api_token === false) {
            return $api_token;
        }
        return hash_equals(
            hash('sha512', 'crypto-lottery'),
            $api_token,
        );
    }
}