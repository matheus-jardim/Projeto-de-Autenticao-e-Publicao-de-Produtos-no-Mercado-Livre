<?php
session_start();

$codeVerifier = base64url_encode(random_bytes(32));
$codeChallenge = base64url_encode(hash('sha256', $codeVerifier, true));

$_SESSION['code_verifier'] = $codeVerifier; 
$_SESSION['code_challenge'] = $codeChallenge; 

function base64url_encode($data) {
    $base64 = base64_encode($data);
    $base64url = strtr($base64, '+/', '-_');
    return rtrim($base64url, '=');
}