<?php
require_once('./challenge.php');
require_once('../../config.php');

$codeChallengeMethod = 'S256';

$state = uniqid();
$_SESSION['state'] = $state;

$authUrl = "https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=$clientId&redirect_uri=$redirectUri&code_challenge=$codeChallenge&code_challenge_method=$codeChallengeMethod&state=$state";

header("Location: $authUrl");
exit;