<?php
session_start();
require_once('./config.php');

$clientId = '691784791424209';
$clientSecret = 'HmrJNuI6rANtVdkVeZPntm0sLOexUzCz';
$redirectUri = 'http://localhost/grupoonline/auth/mercadolivre/callback.php';

// Verifica se o token está expirado ou próximo da expiração
function isTokenExpired($expirationTime)
{
    return (time() + 300) >= strtotime($expirationTime);
}

// Função para sincronizar a conta
function syncAccount()
{
    $codeVerifier = base64url_encode(random_bytes(32));
    $codeChallenge = base64url_encode(hash('sha256', $codeVerifier, true));

    $codeChallengeMethod = 'S256';

    $_SESSION['code_verifier'] = $codeVerifier;
    $state = uniqid();
    $_SESSION['state'] = $state;

    $authUrl = "https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=$clientId&redirect_uri=$redirectUri&code_challenge=$codeChallenge&code_challenge_method=$codeChallengeMethod&state=$state";

    header("Location: $authUrl");
    exit;
}

// Função para trocar o código de autorização por um novo access token

function exchangeAuthorizationCode($authorizationCode)
{
    $codeVerifier = $_SESSION['code_verifier']; // Recupera o code_verifier da sessão

    $tokenUrl = 'https://api.mercadolibre.com/oauth/token';

    $data = [
        'grant_type' => 'authorization_code',
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'code' => $authorizationCode,
        'redirect_uri' => $redirectUri,
        'code_verifier' => $codeVerifier
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($tokenUrl, false, $context);

    $response = json_decode($response);
}

function saveAccountInfo($user_id, $accessToken, $refresh_token, $expires_in)
{
    $sql = $pdo->prepare("INSERT INTO accounts (user_id, access_token, refresh_token, expiration_time)
VALUES (:user_id, :access_token, :refresh_token, :expiration_time)");
    $sql->bindValue(':user_id', $user_id);
    $sql->bindValue(':access_token', $accessToken);
    $sql->bindValue(':refresh_token', $refresh_token);
    $sql->bindValue(':expiration_time', date('Y-m-d H:i:s', $expires_in));

    $sql->execute();
}
