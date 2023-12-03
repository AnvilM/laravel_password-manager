<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use PDO;
use phpseclib3\Crypt\RSA;

class SessionHelper extends Helper
{

    /**
     * Validate API Authentication token.
     *
     * @param  string $token API Authentication token
     * @return bool
     */
    public static function validateToken(string $token): bool
    {
        //Checks if API Authentication token contains ".".
        if (!str_contains($token, '.'))
        {
            return false;
        }


        //Retrun true.
        return true;
    }




    /**
     * Generate API Authentication token.
     *
     * @param array $paylaod Payload data for encryption.
     * @return string Session token.
     */
    public static function generateToken(array $paylaod): string
    {
        $RSA = RSA::loadPrivateKey(env('SECRET'));

        $token = hash('sha256', openssl_random_pseudo_bytes(64))
            . '.'
            . base64_encode($RSA->getPublicKey()->encrypt(json_encode($paylaod)));

        return $token;
    }




    /**
     * Decrypt API Authentication token payload.
     *
     * @param  string $token API Authentication token.
     * @return array Token payload.
     */
    public static function getTokenPayload(string $token): array
    {
        $encryptetPayload = base64_decode(explode('.', $token)[1]);

        $RSA = RSA::loadPrivateKey(env('SECRET'));

        $payload = json_decode($RSA->decrypt($encryptetPayload), true);

        return $payload;
    }
}
