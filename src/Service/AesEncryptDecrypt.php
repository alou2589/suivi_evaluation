<?php

namespace App\Service;

class AesEncryptDecrypt
{


    const CIPHER="@l@ss@net@mbedou25septembre1989+";
    const IV="@l@ss@net@mbedou";
    const METHOD = 'aes-256-cbc';
    public function encrypt($value){
        $encryptedString = openssl_encrypt($value, self::METHOD,
            self::CIPHER, OPENSSL_RAW_DATA, self::IV);
        return base64_encode($encryptedString);
    }

    public function decrypt($value){
        $base64 = base64_decode($value);
        $decryptedString = openssl_decrypt($base64,self:: METHOD,
            self::CIPHER, OPENSSL_RAW_DATA, self::IV);
        return $decryptedString;
    }
}
