<?php

date_default_timezone_set('America/Sao_Paulo');

class User
{
    private $user_id;
    private $access_token;
    private $refresh_token;
    private $expiration_time;
    private $base;

    public function setUser_id($userId)
    {
        $this->user_id = $userId;
    }
    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setAccess_token($accessToken)
    {
        $this->access_token = $accessToken;
    }

    public function getAccess_token()
    {
        return $this->access_token;
    }

    public function setRefresh_token($refreshToken)
    {
        $this->refresh_token = $refreshToken;
    }
    public function getRefresh_token()
    {
        return $this->refresh_token;
    }

    public function setExpiration_time($expirationTime)
    {
        $this->expiration_time = $expirationTime;
    }
    public function getExpiration_time()
    {
        return $this->expiration_time;
    }

    public function setBase($base)
    {
        $this->base = $base;
    }
    public function getBase()
    {
        return $this->base;
    }
    
    public function isTokenExpired()
    {
        return (time() + 300) >= $this->expiration_time;
    }
    
    public function syncAccount($clientId, $redirectUri, $codeChallenge, $codeChallengeMethod, $state)
    {
        $authUrl = "https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=$clientId&redirect_uri=$redirectUri&code_challenge=$codeChallenge&code_challenge_method=$codeChallengeMethod&state=$state";

        header("Location: $authUrl");
        exit;
    }
    
    public function exchangeAuthorizationCode($clientId, $clientSecret, $authorizationCode, $redirectUri, $codeVerifier, $tokenUrl)
    {
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

        try {
            $context = stream_context_create($options);
            $response = file_get_contents($tokenUrl, false, $context);

            $response = json_decode($response);
            $this->setUser_id($response->user_id);
            $this->setAccess_token($response->access_token);
            $this->setRefresh_token($response->refresh_token);
            $this->setExpiration_time(time() + $response->expires_in);
        } catch (Exception $error) {
            header("Location: " . $this->getBase() . "/auth/mercadolivre/auth.php");
            exit;
        }

        return $response;
    }

    public function refreshAccessToken($clientId, $clientSecret, $refresh_token, $tokenUrl)
    {
        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refresh_token
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            ]
        ];

        try {
            $context = stream_context_create($options);
            $response = file_get_contents($tokenUrl, false, $context);

            $response = json_decode($response);
            $this->setUser_id($response->user_id);
            $this->setAccess_token($response->access_token);
            $this->setRefresh_token($response->refresh_token);
            $this->setExpiration_time(time() + $response->expires_in);
        } catch (Exception $error) {
            header("Location: " . $this->getBase() . "/auth/mercadolivre/auth.php");
            exit;
        }

        return $response;
    }
}

interface UserDAO
{
    public function add(User $u);
    public function findByUserId($user_id);
    public function update(User $u);
}