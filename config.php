<?php
$base = 'http://localhost/grupoonline';

$clientId = '691784791424209';
$clientSecret = 'HmrJNuI6rANtVdkVeZPntm0sLOexUzCz';
$redirectUri = "$base/auth/mercadolivre/callback.php";

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

$pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);

$db_name = 'grupoonline';

// CREATE DB IF NOT EXISTS
$createDbQuery = "CREATE DATABASE IF NOT EXISTS $db_name";
$pdo->exec($createDbQuery);

// SELECT DB CREATED
$pdo->exec("USE $db_name");


// TABLE NAME TO BE CREATED
$tableName = 'accounts';

// VERIFY IF TABLE ALREADY EXISTS
$tableExists = false;
$query = $pdo->prepare("SHOW TABLES LIKE '$tableName'");
$query->execute();
$tableExists = $query->rowCount() > 0;

// CREATE TABLE IF DON'T EXISTS
if (!$tableExists) {
    $createTableQuery = "
    CREATE TABLE $tableName (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        access_token VARCHAR(255),
        refresh_token VARCHAR(255),
        expiration_time DATETIME
      )
    ";

    $pdo->exec($createTableQuery);
}
