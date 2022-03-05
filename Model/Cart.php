<?php
require_once 'Model.php';
class Cart extends Model {

    public function getOrderId($username = null, $session = null)
    {
        if ($username != null)
        {
            $sql = "select O.id
                    from orders O join logins l ON o.customer_id = l.customer_id 
                    where l.username = ?
                    and O.status < 2 ";
            $id = $this->executerRequete($sql, array($username));
            if ($id->rowCount() > 0) {
                return $id->fetch();
            }
            else {
                return null; // si on essaie de regarder une commande qui n'existe pas on le fait savoir
            }
        }
        else 
        {
            $sql = "select id from orders WHERE session = ? and registered = 0 and status <2";
            $id = $this->executerRequete($sql, array($session));
            if ($id->rowCount() > 0) {
                return $id->fetch();
            }
            else {
                return null;
            }
        }

    }

    public function getOrderStatus($order_id) {
        $sql = "select status from orders WHERE id = ? and status <2";
        $status = $this->executerRequete($sql, array($order_id));
        if ($status->rowCount() > 0) {
            return $status->fetch();
        }
        else {
            throw new Exception("Aucun status n'est associé à la commande '$order_id'");
        }
    }

    public function getCustomerId($username) {
        $sql = "select customer_id from logins WHERE username = ?";
        $id = $this->executerRequete($sql, array($username));
        if ($id->rowCount() > 0) {
            return $id->fetch();
        }
        else {
            return null;
        }

    }

    
    public function getCartContent($order_id) {
        $sql = "SELECT P.id, P.name, P.image, P.price, O.quantity FROM products P JOIN orderitems O on P.id = O.product_id
        where O.order_id = ?";
        $product = $this->executerRequete($sql, array($order_id));
        if ($product->rowCount() > 0)
        {
            return $product;
        }
        else
            return array();
    }

    public function getTotal($order_id) {
        $sql = "select total from orders WHERE id = ?";
        $total = $this->executerRequete($sql, array($order_id));
        if ($total->rowCount() > 0) {
            return $total->fetch();
        }
        else {
            return 0;
        }
    }


    public function createOrder($customer_id, $session, $registered) // l'id custommer sera 0 si l'utilisateur n'est pas connecté.
    {
        $sql = "insert into orders
        VALUES (NULL,?, ?, NULL, NULL, ?, 0, ?, NULL)";
        try{
            $this->executerRequete($sql, array($customer_id, $registered, date("Y")."-".date("m")."-".date("d"), $session ));
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function removeFromOrder($order_id, $product_id) {
        $sql = "DELETE from orderitems
        where order_id = ? and product_id = ?";
        try {
            $this->executerRequete($sql, array($order_id, $product_id ));
            $this->updateTotal($order_id);
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addProductToOrder($order_id, $product_id, $quantity) {
        $sql1 = "select quantity from orderitems where order_id = ? and product_id = ?";
        try
        {
            $qty = $this->executerRequete($sql1, array($order_id, $product_id));

        }
        catch (Exception $e) {
            return $e->getMessage();
        }
        if ($qty->rowCount() > 0) {
            $sql = "UPDATE orderitems set quantity = ? WHERE order_id = ? and product_id = ?";
            $qty = $qty->fetch();
            $newQuantity = intval($quantity) + intval($qty["quantity"]);

            $quantityAvailableRequest = "SELECT quantity FROM products WHERE id = ?";
            $qtyAvblRqst = $this->executerRequete($quantityAvailableRequest, array($product_id));
            $qtyAvblRqst_SingleRow = $qtyAvblRqst->fetch();
            if ($newQuantity <= $qtyAvblRqst_SingleRow['quantity']) {
                if ($newQuantity == 0) {
                    $this->removeFromOrder($order_id, $product_id);
                }
                else {
                    try{
                        $this->executerRequete($sql, array($newQuantity, $order_id, $product_id));
                    }
                    catch (Exception $e) {
                        return $e->getMessage();
                    }
                } 
            }
        }
        else {
            $sql = "insert into orderitems (order_id, product_id, quantity)
            values (?, ?, ?)";
            try {
                $this->executerRequete($sql, array($order_id, $product_id, $quantity));
            }
            catch (Exception $e) {
                return $e->getMessage();
            }
        }
        $this->updateTotal($order_id);
    }

    public function updateTotal($order_id)
    {
        $sql = "UPDATE orders
        SET total = (SELECT SUM(P.price * O.quantity) FROM orderitems O JOIN products P ON O.product_id = P.id
                    WHERE O.order_id = ?)
        Where id = ?";
        try{
            $this->executerRequete($sql, array($order_id, $order_id));
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

};
