<?php

declare(strict_types=1);

use Firebase\JWT\JWT;

require_once('../vendor/autoload.php');

// Do some checking for the request method here, if desired.


echo password_hash('admin@1234', PASSWORD_DEFAULT);die();

// Attempt to extract the token from the Bearer header
if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
    header('HTTP/1.0 400 Bad Request');
    echo 'Token not found in request';
    exit;
}

$jwt = $matches[1];
if (! $jwt) {
    // No token was able to be extracted from the authorization header
    header('HTTP/1.0 400 Bad Request');
    exit;
}

$secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
JWT::$leeway += 60;
$token = JWT::decode((string)$jwt, $secretKey, ['HS512']);
$now = new DateTimeImmutable();
$serverName = $_SERVER['SERVER_NAME'];

if ($token->iss !== $serverName ||
    $token->nbf > $now->getTimestamp() ||
    $token->exp < $now->getTimestamp())
{
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

// The token is valid, so send the response back to the user
// ...
print_r($token->data->userName);