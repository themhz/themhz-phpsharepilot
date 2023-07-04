<?php

namespace SharePilotV2\Components;
use Google\Service\Keep\User;
use SharePilotV2\Models\Users;

class UserAuth {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

       /* $stmt = $this->db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bindValue(1, $email);
        $stmt->bindValue(2, $hash);
        $stmt->execute();
        $result = $stmt->rowCount() > 0;*/

        $users = new Users();
        $users->email = $email;
        $users->password = $hash;
        $result = $users->insert();

        return $result;

    }

    public function login($email, $password) {

       /* $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bindValue(1, $email);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);*/

        $users = new Users();
        $user = $users->select()->where("email","=",$email)->execute()[0];

        if ($user && password_verify($password, $user['password'])) {
            // password is correct
            // generate a new session token
            $token = bin2hex(openssl_random_pseudo_bytes(16));

            // store the token in the database
            /*$stmt = $this->db->prepare("UPDATE users SET token = ? WHERE id = ?");
            $stmt->bindValue(1, $token);
            $stmt->bindValue(2, $user['id']);
            $stmt->execute();*/

            $users->token = $token;

            $result = $users->update()->where("id","=",$user['id'])->execute();
            if($result){
                $_SESSION["user"] = $user;
                // set the token cookie
                setcookie('token', $token, time() + 3600,'/'); // 1 hour expiration
                return true;
            }
        }
        return false;
    }

    public function authenticate() {

        if (!isset($_COOKIE['token'])) {
            return null;
        }

        $users = new Users();
        $users->token = $_COOKIE['token'];

/*        $stmt = $this->db->prepare("SELECT * FROM users WHERE token = ?");
        $stmt->bindValue(1, $_COOKIE['token']);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);*/


        return $users->select()->where("token","=",$_COOKIE['token'])->execute()[0];
    }
}

