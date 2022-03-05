<?php
require_once 'Model.php';
class AdminPannel extends Model {
    public function registerAdmin($username, $hashedPassword) {
        $sql = "INSERT INTO admin (id, username, password)
                    VALUES (NULL, ?, ?);";
        try{
            $this->executerRequete($sql, array($username, $hashedPassword));
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getOrders() {
        $sql = "SELECT * FROM orders order by id desc";
        $orders = $this->executerRequete($sql);
        return $orders;
    }

    public function getCustomer($id) {
        $sql = "SELECT * FROM customers WHERE id = ?";
        $query = $this->executerRequete($sql, array($id));
        $q = $query->fetch();
        if($id != 0)
        {
            return $q; //On renvoie uniquement la ligne du client, et pas toute la table obtenue dans $query
        }
        else return array('forname' => "Utilisateur", "surname" => "Anonyme");
    }

    public function getAddress($id) {
        $sql = "SELECT * FROM delivery_addresses WHERE id = ?";
        $query = $this->executerRequete($sql, array($id));
        $q = $query->fetch();
        return $q; //On renvoie uniquement la ligne de l'adresse, et pas toute la table obtenue dans $query
    }

    public function getOrderItems($id) {
        $sql = "SELECT * FROM orderitems WHERE order_id = ?";
        $query = $this->executerRequete($sql, array($id));
        return $query;
    }

    public function getItemInfos($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $query = $this->executerRequete($sql, array($id));
        $q = $query->fetch();
        return $q; //On renvoie uniquement la ligne de l'adresse, et pas toute la table obtenue dans $query
    }

    public function confirmOrder($id) {
        $sql = "UPDATE orders SET status = 10 WHERE id = ?";
        try {
            $this->executerRequete($sql, array($id));
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }
};