<?php

require_once 'Model/AdminPannel.php';
require_once 'View/View.php';

class ControllerAdminPannel {

    private $adminPannel;

    public function __construct() {
        $this->adminPannel = new AdminPannel();
    }

    public function ctrlRegisterAdmin($username, $hashedPassword) {
        $this->adminPannel->registerAdmin($username, $hashedPassword);
    }

    public function ctrlConfirmOrder($id) {
        $this->adminPannel->confirmOrder($id);
    }

    public function getOrders() {
        $ordersTable = $this->adminPannel->getOrders();
        $ordersList = array();
        /* On va créer une liste de commandes (orderInfos) dans ordersList, avec :
        orderInfos {
            date
            paymentType
            status
            total
            deliveryAdd {
                add1
                city
                postCode
            }
            customer {
                name
                surname
            }
            itemsList {
                *contient plusieurs instances de item*
                item {
                    quantity
                    name
                    image
                    price
                }
            }
        }
        */
        foreach ($ordersTable as $order) {
            $status = $order['status'];
            if ($status != 0) {
                $date = $order['date'];
                if($order['payment_type'] != null) {
                    $payment_type = $order['payment_type'];
                } else {
                    $payment_type = null;
                }
                $total = $order['total'];

                //Gestion du customer
                $customer_id = $order['customer_id'];
                $customerTable = $this->adminPannel->getCustomer($customer_id);
                $customer = array(
                    "forname" => $customerTable["forname"],
                    "surname" => $customerTable["surname"],
                );

                //Gestion de l'addresse
                $delivery_add_id = $order['delivery_add_id'];
                if ($delivery_add_id != null) {
                    $addressTable = $this->adminPannel->getAddress($delivery_add_id);
                    $address = array(
                        "add1" => $addressTable["add1"],
                        "city" => $addressTable["city"],
                        "postcode" => $addressTable["postcode"],
                    );
                } else {
                    $address = null;
                }

                //Gestion de la liste de produits
                $orderId = $order['id'];
                $itemTable = $this->adminPannel->getOrderItems($orderId);
                $itemList = array(); //Liste dans laquelle seront rangés chaque items, eux même des listes d'informations sur l'item en question
                foreach ($itemTable as $orderItemLine) {
                    $quantity = $orderItemLine['quantity'];

                    $itemId =  $orderItemLine['product_id'];
                    $itemInfos = $this->adminPannel->getItemInfos($itemId);
                    $name = $itemInfos["name"];
                    $image = $itemInfos["image"];
                    $price = $itemInfos["price"];

                    $item = array(
                        "quantity" => $quantity,
                        "name" => $name,
                        "image" => $image,
                        "price" => $price
                    );
                    array_push($itemList, $item);
                }

                //Ajout de toutes ces infos à l'array de la commande orderInfos
                $orderInfos = array(
                    "id" => $orderId,
                    "date" => $date,
                    "payment_type" => $payment_type,
                    "status" => $status,
                    "total" => $total,
                    "customer" => $customer,
                    "address" => $address,
                    "itemList" => $itemList
                );

                array_push($ordersList, $orderInfos);
            }
        }
        return $ordersList;
    }

    public function showPannel($registerValidationMessage = "") {
        $ordersList = $this->getOrders();
        $view = new View("AdminPannel");
        $view->generate(array('ordersList' => $ordersList, 'registerValidationMessage' => $registerValidationMessage));
    }


}