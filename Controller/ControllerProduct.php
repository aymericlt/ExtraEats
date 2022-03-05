<?php

require_once 'Model/Product.php';
require_once 'View/View.php';

class ControllerProduct {

    private $product;

    public function __construct() {
        $this->product = new Product();
    }

// Affiche la liste de tous les trucs à acheter
    public function ctrlAddReview($id_product, $name, $photoUser, $stars, $title, $description)
    {
        $this->product->addReview($id_product, $name, $photoUser, $stars, $title, $description);
    }

    public function showProduct($id) {
        $product = $this->product->getProduct($id);
        $reviews = $this->product->getReviews($id);
        $view = new View("Product");
        $view->generate(array('product' => $product, 'reviews' => $reviews));
    }
}