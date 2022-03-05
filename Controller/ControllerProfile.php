<?php

require_once 'Model/Profile.php';
require_once 'Model/Checkout.php';
require_once 'View/View.php';

class ControllerProfile {

    private $profile;

    public function __construct() {
        $this->profile = new Profile();
    }

    public function ctrlGeneratePDF($orderId)
    {
        return $this->profile->generatePDF($orderId);
    }

    public function ctrlPayOrder($orderId, $payment_type)
    {
        return $this->profile->payOrder($orderId, $payment_type);
    }

    public function getOrders($session,$username) {
        $ordersTable = $this->profile->getAllOrders($session,$username);
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
            $date = $order['date'];
            if($order['payment_type'] != null) {
                $payment_type = $order['payment_type'];
            } else {
                $payment_type = null;
            }
            $total = $order['total'];

            //Gestion du customer
            $customer_id = $order['customer_id'];
            $customerTable = $this->profile->getCustomer($customer_id);
            $customer = array(
                "forname" => $customerTable["forname"],
                "surname" => $customerTable["surname"],
            );

            //Gestion de l'addresse
            $delivery_add_id = $order['delivery_add_id'];
            if ($delivery_add_id != null) {
                $addressTable = $this->profile->getAddress($delivery_add_id);
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
            $itemTable = $this->profile->getOrderItems($orderId);
            $itemList = array(); //Liste dans laquelle seront rangés chaque items, eux même des listes d'informations sur l'item en question
            foreach ($itemTable as $orderItemLine) {
                $quantity = $orderItemLine['quantity'];

                $itemId =  $orderItemLine['product_id'];
                $itemInfos = $this->profile->getItemInfos($itemId);
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
        return $ordersList;
    }

    public function showProfile($session,$username) {
        $ordersList = $this->getOrders($session,$username);
        $view = new View("Profile");
        $view->generate(array('ordersList' => $ordersList));
    }


}