<?php
require_once 'Model.php';
class Login extends Model {
    public function getUser($username, $hashedPassword) {
        $sql = "SELECT * FROM logins WHERE username = ? AND password = ?";
        $query = $this->executerRequete($sql, array($username, $hashedPassword));
        if ($query->rowCount() == 1) {
            return $query;
        }
        else
            return null;
    }

    public function getAdmin($username, $hashedPassword) {
        $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
        $query = $this->executerRequete($sql, array($username, $hashedPassword));
        if ($query->rowCount() == 1) {
            return $query;
        }
        else
            return null;
    }
};
