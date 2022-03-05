<?php
require_once 'Model.php';
class Checkout extends Model {
    
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

    public function setOrderStatus($order_id, $status)
    {
        $sql = "update orders set status = ? WHERE id = ?";
        try{
            $this->executerRequete($sql, array($status, $order_id));          
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTotal($order_id)
    {
        $sql = "select total from orders WHERE id = ?";
        $total = $this->executerRequete($sql, array($order_id));
        if ($total->rowCount() > 0) {
            return $total->fetch();
        }
        else {
            return 0;
        }
    }
}
