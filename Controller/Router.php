<?php

require_once 'Controller/ControllerCatalog.php';
require_once 'Controller/ControllerProduct.php';
require_once 'Controller/ControllerCart.php';
require_once 'Controller/ControllerLogin.php';
require_once 'Controller/ControllerRegister.php';
require_once 'Controller/ControllerAdminPannel.php';
require_once 'Controller/ControllerCheckout.php';
require_once 'Controller/ControllerProfile.php';
require_once 'assets/fpdf/createOrderPDF.php';


require_once 'View/View.php';
class Router {

    private $ctrlCatalog;
    private $ctrlProduct;
    private $ctrlCart;
    private $ctrlLogin;
    private $ctrlRegister;
    private $ctrlAdminPannel;
    private $ctrlCheckout;
    private $ctrlProfile;

    public function __construct() {
        $this->ctrlCatalog = new ControllerCatalog();
        $this->ctrlProduct = new ControllerProduct();
        $this->ctrlCart = new ControllerCart();
        $this->ctrlLogin = new ControllerLogin();
        $this->ctrlRegister = new ControllerRegister();
        $this->ctrlAdminPannel = new ControllerAdminPannel();
        $this->ctrlCheckout = new ControllerCheckout();
        $this->ctrlProfile = new ControllerProfile();
    }
    
    public function routing(){
        try {
            if (isset($_GET["reset"])) //Pour changer le status de la commande si l'utilisateur décide de l'annuler.
            {
                $this->ctrlCheckout->ctrlSetOrderStatus($_SESSION["SESS_ORDERNUM"],0);
                if($_SESSION["logged"])
                {
                    $this->setSessionOrder($_SESSION["username"]);
                }
                else 
                {
                    $this->setSessionOrder();
                }
            }
            if (isset($_GET['page'])) {
                switch ($_GET['page'])
                {
                    case 'catalog':
                        $this->routCatalog();
                        break;
                    
                    case 'product':
                        if(isset($_GET['id'])){
                            $this->routProduct();
                            break;
                        }
                        else {
                            $this->routCatalog();
                            break;
                        }
                    
                    case 'login':
                        if (!$_SESSION["logged"]) { //Si l'utilisateur n'est pas connecté : affichage de la page connexion
                            $this->routLogin();
                            break;
                        }
                        else {// Si l'utilisateur est connecté, on le déconnecte
                            $_SESSION["logged"] = false;
                            $_SESSION["logged_as_admin"] = false;
                            $_SESSION["username"] = null;
                            $this->setSessionOrder();
                            header('Location: index.php');
                            break;
                        }
                    
                    case 'register':
                        $this->routRegister();
                        break;
                    
                    case 'adminPannel':
                        if ($_SESSION["logged_as_admin"]) {
                            $this->routAdminPannel();
                        } else {
                            $this->ctrlCatalog->accueil(0);
                        }
                        break;
                    
                    case 'Cart':
                        if (!$_SESSION["logged"]) { //Si l'utilisateur n'est pas connecté : affichage de la page connexion
                            $this->setSessionOrder();
                        }

                        if ($_SESSION["SESS_ORDERSTATUS"] == 1)
                        {
                            $this->routCheckout();
                            break;
                        }
                        
                        $this->routCart();
                        break;
                    
                    case 'checkout':
                        if (isset($_POST['continueToCheckout']) || $_SESSION["SESS_ORDERSTATUS"] == 1)
                        {
                            if ($_SESSION["SESS_ORDERSTATUS"] == 0)
                            {
                                $this->ctrlCheckout->ctrlSetOrderStatus($_SESSION["SESS_ORDERNUM"],1);
                                if($_SESSION["logged"])
                                {
                                    $this->setSessionOrder($_SESSION["username"]);
                                }
                                else 
                                {
                                    $this->setSessionOrder();
                                }
                                
                            }
                            $this->routCheckout();
                            break;
                        }
                        else {
                            header('Location: index.php');
                        }

                    case 'profile':
                        $this->routProfile();
                        break;
                        

                    
                    default:
                        throw new Exception("Action non valide");
                        break;
                }
            }
            else{
                $this->ctrlCatalog->accueil(0);
            }


        }

        catch (Exception $e) {
            $this->erreur($e->getMessage());
        }

    }

    private function routCatalog(){
        if (isset($_POST['searchItemsRequest'])) {
            $txtSearched = $this->getParameter($_POST,"searchTextZone");
            $this->ctrlCatalog->accueil(0, $txtSearched);
        } else {
            if (isset($_GET['cat'])) {
                $this->ctrlCatalog->accueil(intval($_GET['cat']));
            }
            else {
                $this->ctrlCatalog->accueil(0);
            }
        }
    }

    private function routProduct(){
        $id = intval($this->getParameter($_GET, 'id'));
        $continue = true; // Booléen attestant la validité du formulaire
        if (isset($_POST["confirm-review"])) //Si le formulaire a ete rempli au moins une fois
        {
            $this->ctrlProduct->ctrlAddReview(intval($this->getParameter($_GET, 'id')), $this->getParameter($_POST, "review_form_name_user" ), $this->getParameter($_POST, "review_form_photo_user"), 
                                                    $this->getParameter($_POST, "review_form_stars"), $this->getParameter($_POST, "review_form_title"), $this->getParameter($_POST, "review_form_description"));
            header("Location: index.php?page=product&id=".$_GET['id']."#addReviewSection");//on redirige vers la page du produit
            
        }
        $this->ctrlProduct->showProduct($id);
    }

    private function routLogin(){
        if (isset($_POST["login-request"])) {
            $username = $this->getParameter($_POST, "login_form_username");
            $hashedPassword = sha1($this->getParameter($_POST, "login_form_password")); //Exemple de login : username : Aymeric0, mdp : lol
            
            $query = $this->ctrlLogin->ctrlGetUser($username, $hashedPassword);
            if ($query == null) {
                //Si l'utilisateur n'est pas reconnu comme client, on lance une requete sur la table admin avant d'afficher une erreur
                $queryCheckAdmin = $this->ctrlLogin->ctrlGetAdmin($username, $hashedPassword);
                if ($queryCheckAdmin != null) { //L'utilisateur est un admin
                    $_SESSION["logged"] = true;
                    $_SESSION["username"] = $username;
                    $this->setSessionOrder($username);
                    $_SESSION["logged_as_admin"] = true;
                    header('Location: index.php'); //Et on redirige l'utilisateur vers l'accueil
                } else { //L'utilisateur n'est pas reconnu
                    $errorMessage = "Utilisateur non reconnu, veuillez vérifier les informations entrées";
                    $this->ctrlLogin->showLoginPage($errorMessage);
                }
            } else { //La connexion a bien été faite ici
                //$q = $query->fetch(); //On conserve dans $q la premiere ligne de la requete $query, c'est à dire la seule
                $_SESSION["logged"] = true;
                $_SESSION["username"] = $username;
                $this->setSessionOrder($username);
                $_SESSION["logged_as_admin"] = false;
                header('Location: index.php'); //Et on redirige l'utilisateur vers l'accueil
            }
        } 
        else {
            $this->ctrlLogin->showLoginPage();
        }
    }

    private function routRegister(){
        if (isset($_POST["register-request"])) {
            $username = $this->getParameter($_POST,"register_form_username");
            $hashedPassword = sha1($this->getParameter($_POST,"register_form_password"));
            $hashedPasswordConfirmation = sha1($this->getParameter($_POST,"register_form_password_confirmation"));

            $email = $this->getParameter($_POST,"register_form_email");//On récupère également l'adresse mail pour effectuer une vérification sur la table customers
            if (strlen($username) < 1) {
                $errorMessage = "Veuillez entrer un nom d'utilisateur";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            } 
            else if (strlen($this->getParameter($_POST,"register_form_password")) < 1) {
                $errorMessage = "Veuillez entrer un nom mot de passe";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            } 
            else if (strlen($this->getParameter($_POST,"register_form_password_confirmation")) < 1) {
                $errorMessage = "Veuillez confirmer votre mot de passe";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else if ($hashedPasswordConfirmation != $hashedPassword) {
                $errorMessage = "Le mot de passe n'a pas été correctement confirmé";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else if ($this->ctrlRegister->ctrlUserAlreadyExists($username)) {
                $errorMessage = "Ce nom d'utilisateur existe déjà";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else if ($this->ctrlRegister->ctrlEMailAlreadyExists($email)) {
                $errorMessage = "Cette adresse mail a déjà été utilisée";
                $this->ctrlRegister->showRegisterPage($errorMessage);
            }
            else { //L'inscription est valide, on peut enregister l'utilisateur, en récupérant les informations personnelles
                $firstname = $this->getParameter($_POST,"register_form_firstname");
                $surname = $this->getParameter($_POST,"register_form_surname") ;
                $add1 = $this->getParameter($_POST,"register_form_add1");
                $add2 = $this->getParameter($_POST,"register_form_add2");
                $city = $this->getParameter($_POST,"register_form_city");
                $postcode = $this->getParameter($_POST,"register_form_postcode");
                $phone = $this->getParameter($_POST,"register_form_phone");

                $this->ctrlRegister->ctrlRegisterUser($username, $hashedPassword, $firstname, 
                    $surname, $add1, $add2, $city, $postcode, $phone, $email);
                $_SESSION["logged"] = true; //Une fois enregistré on connecte l'utilisateur
                $_SESSION["username"] = $username;
                $this->setSessionOrder($username);

                
                header('Location: index.php');
            }

        } 
        else {
            $this->ctrlRegister->showRegisterPage();
        }

    }

    private function routCart()
    {
        if (isset($_POST['addedToCart']))
        {
            if ($_SESSION["SESS_ORDERNUM"] == null)
            {
                if($_SESSION['logged'] == true){
                    $result = $this->ctrlCart->ctrlGetCustomerIdFromUsername($_SESSION["username"]);
                    $this->ctrlCart->ctrlCreateOrder($result['customer_id'], session_id(), 1);
                    $this->setSessionOrder($_SESSION["username"]);
                }
                else 
                {
                    $this->ctrlCart->ctrlCreateOrder(0, session_id(), 0);
                    $this->setSessionOrder();
                }

            }

            $this->ctrlCart->ctrlAddItemToOder($_SESSION["SESS_ORDERNUM"], $_POST['idProduct'], $_POST['hiddenQuantity']); //Remplacer 1 par $_POST['hiddenQuantity']
        }

        if (isset($_POST["removeItemRequest"]))
        {
            $this->ctrlCart->ctrlRemoveItemFromCart($_SESSION["SESS_ORDERNUM"], $this->getParameter($_POST, "itemToRemoveId"));
        }

        if ($_SESSION["SESS_ORDERNUM"] != null) // si la session / l'utilisateur a déja un panier on l'affiche
        {
            $this->ctrlCart->showCart($_SESSION["SESS_ORDERNUM"]);
        }
        else $this->erreur("Ajoutez des produits au panier avant de le consulter.");

    }

    private function routAdminPannel() {
        if (isset($_POST['createPDF'])) {
            $pdf = new myPDF('P', 'mm', 'A4');
            $customer_forname = $this->getParameter($_POST, "PDF_customer_forname");
            $customer_surname = $this->getParameter($_POST, "PDF_customer_surname");
            $address_add1 = $this->getParameter($_POST, "PDF_address_add1");
            $address_city = $this->getParameter($_POST, "PDF_address_city");
            $address_postcode = $this->getParameter($_POST, "PDF_address_postcode");
            $payment_type = $this->getParameter($_POST, "PDF_payment_type");
            $date = $this->getParameter($_POST, "PDF_date");
            $total = $this->getParameter($_POST, "PDF_total");
            $pdf->createOrderPDF($customer_forname, $customer_surname, $address_add1, $address_city,
                    $address_postcode, $payment_type, $date, $total);
        }

        if (isset($_POST['register-request'])) {
            $username = $this->getParameter($_POST,"register_form_username_admin");
            $hashedPassword = sha1($this->getParameter($_POST,"register_form_password_admin"));
            $hashedPasswordConfirmation = sha1($this->getParameter($_POST,"register_form_password_confirmation_admin"));
            if ($hashedPasswordConfirmation != $hashedPassword) {
                $registerValidationMessage = "Le mot de passe n'a pas été correctement confirmé";
                $this->ctrlAdminPannel->showPannel($registerValidationMessage);
            }
            else if ($this->ctrlRegister->ctrlUserAlreadyExists($username)) {
                $registerValidationMessage = "Ce nom d'utilisateur existe déjà";
                $this->ctrlAdminPannel->showPannel($registerValidationMessage);
            }
            else {
                $this->ctrlAdminPannel->ctrlRegisterAdmin($username, $hashedPassword);
                $registerValidationMessage = "Inscription validée. ".$username." est désormais un administrateur.";
                $this->ctrlAdminPannel->showPannel($registerValidationMessage);
            }
        } 
        else if (isset($_POST['confirmOrder'])) {
            $orderId = $this->getParameter($_POST,"idOrder");
            $this->ctrlAdminPannel->ctrlConfirmOrder($orderId);
            $this->ctrlAdminPannel->showPannel();
        } 
        else {
            $this->ctrlAdminPannel->showPannel();
        }

    }

    private function routCheckout()
    {
        $total = $this->ctrlCheckout->ctrlGetTotal($_SESSION["SESS_ORDERNUM"]);
        $total = $total['total'];
        $this->ctrlCheckout->showCheckout($total);
    }

    private function routProfile()
    {
        if (isset($_POST['createPDF'])) {
            $pdf = new myPDF('P', 'mm', 'A4');
            $customer_forname = $this->getParameter($_POST, "PDF_customer_forname");
            $customer_surname = $this->getParameter($_POST, "PDF_customer_surname");
            $address_add1 = $_POST["PDF_address_add1"];
            $address_city = $this->getParameter($_POST, "PDF_address_city");
            $address_postcode = $this->getParameter($_POST, "PDF_address_postcode");
            $payment_type = $this->getParameter($_POST, "PDF_payment_type");
            $date = $this->getParameter($_POST, "PDF_date");
            $total = $this->getParameter($_POST, "PDF_total");
            $pdf->createOrderPDF($customer_forname, $customer_surname, $address_add1, $address_city,
                    $address_postcode, $payment_type, $date, $total);
        }
        if (isset($_POST["checkout-request"]))
        {
            $this->ctrlCheckout->ctrlAddAdressToOrder($_SESSION["SESS_ORDERNUM"], $this->getParameter($_POST, "checkout_form_firstname"), 
                                                    $this->getParameter($_POST, "checkout_form_surname"), $this->getParameter($_POST, "checkout_form_add1"),
                                                    $this->getParameter($_POST, "checkout_form_city"), $this->getParameter($_POST, "checkout_form_postcode"),
                                                    $this->getParameter($_POST, "checkout_form_phone"), $this->getParameter($_POST, "checkout_form_email"));
            $this->ctrlProfile->ctrlPayOrder($_SESSION["SESS_ORDERNUM"], $this->getParameter($_POST, "checkout_form_paymentType"));
            if($_SESSION["logged"])
            {
                $this->setSessionOrder($_SESSION["username"]);
            }
            else 
            {
                $this->setSessionOrder();
            }
        }
        $this->ctrlProfile->showProfile(session_id(), $_SESSION["username"]); 
    }


    // Affiche une erreur
    private function erreur($msgErreur) {
        $vue = new View("Error");
        $vue->generate(array('msgErreur' => $msgErreur));
    }

    private function setSessionOrder($username = null)
    {
        $orderNum = $this->ctrlCart->ctrlGetOrderId($username, session_id());
        if ($orderNum == null)
        {
            $_SESSION["SESS_ORDERNUM"] = $orderNum;
            $_SESSION["SESS_ORDERSTATUS"] = null;
        }
        else
        {
            $_SESSION["SESS_ORDERNUM"] = intval($orderNum["id"]);
            $orderstatus = $this->ctrlCart->ctrlGetOrderStatus(intval($orderNum["id"]));
            $_SESSION["SESS_ORDERSTATUS"] = intval($orderstatus["status"]);
        }
    }

    private function getParameter($tableau, $nom) { 
        if (isset($tableau[$nom])) { 
            return $tableau[$nom]; 
        } 
        else throw new Exception("Paramètre '$nom' absent"); 
    }


}