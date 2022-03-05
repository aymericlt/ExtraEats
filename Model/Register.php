<?php
require_once 'Model.php';
class Register extends Model {
    public function userAlreadyExists($username) {
        $sql = "SELECT * FROM logins WHERE username = ?";
        $query = $this->executerRequete($sql, array($username));
        if ($query->rowCount() > 0) {
            return true; //Cet utilisateur existe déjà
        }
        else
            return false; //Cet utilisateur n'existe pas
    }

    public function eMailAlreadyExists($email) {
        $sql = "SELECT * FROM customers WHERE email = ?";
        $query = $this->executerRequete($sql, array($email));
        if ($query->rowCount() > 0) {
            return true; //Cet email existe déjà
        }
        else
            return false; //Cet email n'existe pas
    }

    public function getCustomerId($email) {
        $sql = "SELECT * FROM customers WHERE email = ?";
        $query = $this->executerRequete($sql, array($email));
        $q = $query->fetch(); //On gagne la première ligne de la requête, c'est à dire la seule
        return $q['id'];
    }

    public function registerUserInCustomers($firstname, $surname, $add1, $add2, $city, $postcode, $phone, $email) {
        $sql = "INSERT INTO customers (id, forname, surname, add1, add2, add3, postcode, phone, email, registered)
                    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, '1');";
        try{
            $this->executerRequete($sql, array($firstname, $surname, $add1, $add2, $city, $postcode, $phone, $email));
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function registerUserInLogins($idCustomer, $username, $hashedPassword) {
        $sql = "INSERT INTO logins (id, customer_id, username, password)
                    VALUES (NULL, ?, ?, ?);";
        try{
            $this->executerRequete($sql, array($idCustomer, $username, $hashedPassword));
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
};
