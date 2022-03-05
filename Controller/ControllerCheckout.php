<?php

require_once 'Model/Checkout.php';
require_once 'View/View.php';

class ControllerCheckout {

    private $checkout;

    public function __construct() {
        $this->checkout = new Checkout();
    }

    public function ctrlAddAdressToOrder($order_id, $firstname, $lastname, $add1, $city, $postcode, $phone, $email)
    {
        return $this->checkout->addAdressToOrder($order_id, $firstname, $lastname, $add1, $city, $postcode, $phone, $email);
    }

    public function ctrlSetOrderStatus($order_id, $status)
    {
        return $this->checkout->setOrderStatus($order_id, $status);
    }
    
    public function ctrlGetTotal($order_id)
    {
        return $this->checkout->getTotal($order_id);
    }
    public function showCheckout($price)
    {
        $view = new View("Checkout");
        $view->generate(array('price' => $price));
    }

}