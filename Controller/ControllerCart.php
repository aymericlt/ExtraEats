<?php

require_once 'Model/Cart.php';
require_once 'View/View.php';

class ControllerCart {

    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }



    public function ctrlGetOrderStatus($order_id)
    {
        return $this->cart->getOrderStatus($order_id);
    }

    public function ctrlRemoveItemFromCart($order_id, $product_id)
    {
        return $this->cart->removeFromOrder($order_id, $product_id);
    }

    public function ctrlGetOrderId($username = null, $session = null)
    {
        return $this->cart->getOrderId($username, $session);
    }

    public function ctrlGetCustomerIdFromUsername($username)
    {
        return $this->cart->getCustomerId($username);
    }

    public function ctrlCreateOrder($customer_id, $session, $registered)
    {
        return $this->cart->createOrder($customer_id, $session, $registered);
    }

    public function ctrlAddItemToOder($order_id, $product_id, $quantity) {
        //Ajoute une ligne à la table orderitem en indiquant quel produit et en quelle quantité il a été ajouté
        $this->cart->addProductToOrder($order_id, $product_id, $quantity);
    }
    
    public function showCart($order_id) {
        // Affiche la liste des articles dans le panier, et les informations pour regler
        $products = $this->cart->getCartContent($order_id);
        $total = $this->cart->getTotal($order_id);
        $view = new View("Cart");
        $view->generate(array('products' => $products, 'total' => $total));
    }

}

