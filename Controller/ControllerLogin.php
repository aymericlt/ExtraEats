<?php

require_once 'Model/Login.php';
require_once 'View/View.php';

class ControllerLogin {

    private $login;

    public function __construct() {
        $this->login = new Login();
    }

    public function ctrlGetUser($username, $hashedPassword) {
        return $this->login->getUser($username, $hashedPassword);
    }

    public function ctrlGetAdmin($username, $hashedPassword) {
        return $this->login->getAdmin($username, $hashedPassword);
    }
    
    public function showLoginPage($errorMessage = "") {
        $view = new View("Login");
        $view->generate(array('errorMessage' => $errorMessage));
    }

}

