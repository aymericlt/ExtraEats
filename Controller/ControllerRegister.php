<?php

require_once 'Model/Register.php';
require_once 'View/View.php';

class ControllerRegister {

    private $register;

    public function __construct() {
        $this->register = new Register();
    }

    public function ctrlUserAlreadyExists($username) {
        return ($this->register->userAlreadyExists($username)); //Si l'utilisateur existe déjà on renvoie true
    }

    public function ctrlEMailAlreadyExists($email) {
        return ($this->register->eMailAlreadyExists($email)); //Si l'utilisateur existe déjà on renvoie true
    }

    public function ctrlRegisterUser($username, $hashedPassword, $firstname, $surname, $add1, $add2, $city, $postcode, $phone, $email) {
        //Pour enregistrer un nouvel utilisateur on l'enregistre d'abord sur la table customers, puis on récupère l'id de la ligne créée pour l'injecter comme id_customer d'une nouvelle
        //entrée de la table logins
        $this->register->registerUserInCustomers($firstname, $surname, $add1, $add2, $city, $postcode, $phone, $email);
        $idCustomer = $this->register->getCustomerId($email);
        $this->register->registerUserInLogins($idCustomer, $username, $hashedPassword);
    }
    
    public function showRegisterPage($errorMessage = "") {
        $view = new View("Register");
        $view->generate(array('errorMessage' => $errorMessage));
    }

}

