<?php
session_start();
require_once('../../config.php');
require_once('./models/User.php');
require_once('./models/UserDAOMysql.php');

// VERIFY STATE
$state = $_GET['state'];

if ($_SESSION['state'] !== $state) {
    header("Location: $base/auth/mercadolivre/auth.php");
    exit;
}

unset($_SESSION['state']);
unset($_SESSION['access_token']);

$tokenUrl = 'https://api.mercadolibre.com/oauth/token';

$user = new User();
$userDAO = new UserDAOMysql($pdo, $tableName);

$user->setBase($base);

// VERIFY IF USER IS AUTHENTICATED
if (!$user->getAccess_token()) {

    // VERIFY IF AUTHORIZATION CODE IS PRESENT
    if (isset($_GET['code'])) {
        $authorizationCode = $_GET['code'];
        $codeVerifier = $_SESSION['code_verifier']; 

        // CHANGE THE AUTHRORIZATION CODE FOR A NEW ACCESS TOKEN
        $response = $user->exchangeAuthorizationCode($clientId, $clientSecret, $authorizationCode, $redirectUri, $codeVerifier, $tokenUrl);

        if (!$userDAO->findByUserId($user->getUser_id())) {
            $userDAO->add($user);
        } else {
            $userDAO->update($user);
        }
    } else {
        $user->syncAccount($clientId, $redirectUri, $_SESSION['code_challenge'], 'S256', $_SESSION['state']);
    }
}

// VERIFY IF TOKEN IS EXPIRING
if ($user->isTokenExpired()) {
    // RENEW ACCESS TOKEN USING REFRESH TOKEN
    $response = $user->refreshAccessToken($clientId, $clientSecret, $user->getRefresh_token(), $tokenUrl);

    // UPDATE USER IN DB WHITH NEW TOKEN
    $userDAO->update($user);
}

$_SESSION['access_token'] = $user->getAccess_token();
$_SESSION['expire'] = $user->getExpiration_time();

header("Location: $base");
exit;
