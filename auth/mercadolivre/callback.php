<?php
session_start();
require_once('./config.php');;
require_once('./models/User.php');
require_once('./models/UserDAOMysql.php');

$tokenExpired = false;

$clientId = '691784791424209';
$clientSecret = 'HmrJNuI6rANtVdkVeZPntm0sLOexUzCz';
$redirectUri = 'http://localhost/grupoonline/auth/mercadolivre/callback.php';


$state = $_GET['state'];

if ($_SESSION['state'] !== $state) {
    header('Location: http://localhost/grupoonline/auth/mercadolivre/auth.php');
    exit;
}

unset($_SESSION['state']);

$tokenUrl = 'https://api.mercadolibre.com/oauth/token';

$user = new User();
$userDAO = new UserDAOMysql($pdo);

// Verifica se o usuário está autenticado
if (!$user->getAccess_token()) {   

    // Verifica se o código de autorização está presente
    if(isset($_GET['code'])) {
        $authorizationCode = $_GET['code'];
        $codeVerifier = $_SESSION['code_verifier']; // Recupera o code_verifier da sessão
       
        // Troca o código de autorização por um novo access token
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
echo ((time() + 300) >= $user->getExpiration_time())?'Expirou': 'Válido';
echo '<br/>';
echo (time() + 300);
echo '<br/>';
echo $user->getExpiration_time();

// Verifica se o token está expirado ou próximo da expiração
if ($user->isTokenExpired()) {    
    // Renova o access token usando o refresh token
    $response = $user->refreshAccessToken($clientId, $clientSecret, $user->getRefresh_token(),$tokenUrl);
    
    // Atualiza o usuário no banco de dados com o novo token
    $userDAO->update($user);
}

