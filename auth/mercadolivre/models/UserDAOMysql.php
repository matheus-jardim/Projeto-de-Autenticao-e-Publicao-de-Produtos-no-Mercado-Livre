<?php
require_once('./models/User.php');

date_default_timezone_set('America/Sao_Paulo');

class UserDAOMysql implements UserDAO
{
    private $pdo;
    private $tableName;
    
    public function __construct(PDO $driver, $tableName)
    {
        $this->pdo = $driver;
        $this->tableName = $tableName;
    }

    public function add(User $u)
    {
        $sql = $this->pdo->prepare("INSERT INTO ".$this->tableName." (user_id, access_token, refresh_token, expiration_time)
        VALUES (:user_id, :access_token, :refresh_token, :expiration_time)");
        $sql->bindValue(':user_id', $u->getUser_id());
        $sql->bindValue(':access_token', $u->getAccess_token());
        $sql->bindValue(':refresh_token', $u->getRefresh_token());
        $sql->bindValue(':expiration_time', date('Y-m-d H:i:s', $u->getExpiration_time()));

        $sql->execute();
    }

    public function findByUserId($user_id)
    {
        $sql = $this->pdo->prepare('SELECT * FROM '.$this->tableName.' WHERE user_id = :user_id');
        $sql->bindValue(':user_id', $user_id);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $data = $sql->fetch();
            $u = new User();
            $u->setUser_id($data['user_id']);
            $u->setAccess_token($data['access_token']);
            $u->setRefresh_token($data['refresh_token']);
            $u->setExpiration_time(strtotime($data['expiration_time']));
            return $u;
        } else {
            return false;
        }
    }

    public function update (User $u) {
        $sql = $this->pdo->prepare('UPDATE '.$this->tableName.' 
        SET access_token = :access_token, refresh_token= :refresh_token, expiration_time= :expiration_time
        WHERE user_id = :user_id');
        $sql->bindValue(':access_token', $u->getAccess_token());
        $sql->bindValue(':refresh_token', $u->getRefresh_token());
        $sql->bindValue(':expiration_time', date('Y-m-d H:i:s', $u->getExpiration_time()));
        $sql->bindValue(':user_id', $u->getUser_id());
        $sql->execute();
        return true;
    }
}
