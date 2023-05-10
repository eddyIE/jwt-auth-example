<?php

declare(strict_types=1);

use Firebase\JWT\JWT;

require_once('../vendor/autoload.php');
require_once('../config/connection.php');


// Validate the credentials against a database, or other data store.
$request = json_decode(array_key_first($_REQUEST), true);

if ($user = get("username = '{$request["username"]}'")) {
    if (password_verify($request['password'], $user[0]['password'])) {
        $hasValidCredentials = true;
    }
} else {
    $hasValidCredentials = false;
}


if ($hasValidCredentials) {
    $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    $tokenId    = base64_encode(random_bytes(16));
    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Add 60 seconds
    $serverName = $_SERVER['SERVER_NAME'];
    $username   = $request['username'];                                 // Retrieved from filtered POST data

    // Create the token as an array
    $data = [
        'iat'  => $issuedAt->getTimestamp(),    // Issued at: time when the token was generated
        'jti'  => $tokenId,                     // Json Token Id: an unique identifier for the token
        'iss'  => $serverName,                  // Issuer
        'nbf'  => $issuedAt->getTimestamp(),    // Not before
        'exp'  => $expire,                      // Expire
        'data' => [                             // Data related to the signer user
            'userName' => $username,            // User name
        ]
    ];

    // Encode the array to a JWT string.
    echo JWT::encode(
        $data,      //Data to be encoded in the JWT
        $secretKey, // The signing key
        'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
    );
}
