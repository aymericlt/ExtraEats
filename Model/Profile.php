<?php
require_once 'Model.php';
class Profile extends Model {
    
    public function addAdressToOrder($order_id, $firstname, $lastname, $add1, $city, $postcode, $phone, $email)
    {
        $sql = "INSERT INTO delivery_addresses VALUES
        (null, ?, ?, ?, null, ? ,?, ?, ?)";
        $sql2 = "update orders set delivery_add_id = ? WHERE id = ?";
        try{
            $this->executerRequete($sql, array($firstname, $lastname, $add1, $city, $postcode, $phone, $email));
            $this->executerRequete($sql2, array($this->getLastInsertId(), $order_id));
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllOrders($session, $username)
    {
        if($username == null)
        {
            $sql = "select * from orders where session = ? and registered = 0 and status != 0 order by id desc";
            $result = $this->executerRequete($sql, array($session));
            return $result;
        }
        else{
            $sql = "select O.* FROM orders O JOIN customers C on C.id = O.customer_id 
            WHERE C.id = (SELECT customer_id from logins WHERE username = ?) and O.status != 0 order by id desc";
            $result = $this->executerRequete($sql, array($username));
            return $result;
        }

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

    public function payOrder($orderId, $paymentType)
    {
        $sql = "update orders set status = 2 ,payment_type = ? where id = ?";
        try{
            switch ($paymentType){
                case "1":
                $this->executerRequete($sql, array("Paypal", $orderId));
                break;

                case "2":
                $this->executerRequete($sql, array("Chèque", $orderId));
                break;

                case "3":
                $this->executerRequete($sql, array("CB", $orderId));
                break;

                default:
                $this->executerRequete($sql, array("Moyen de paiement", $orderId));
                break;  
            } 
        }
        catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function generatePDF($order_id)
    {

    }
}
