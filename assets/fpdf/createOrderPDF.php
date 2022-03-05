<?php

require "fpdf.php";

class myPDF extends FPDF {
    function Header() {
        $this->Image('assets/img/shopify3.png',10,6,30);
        $this->SetFont('Arial','B',15);
        $this->Cell(70);
        $this->Cell(100,10,'Facture Extra Eats',1,0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    function createOrderPDF($customer_forname, $customer_surname, $address_add1, $address_city, $address_postcode, $payment_type, $date, $total) {
        $this->AddPage();

        $this->Cell(100, 20, '', 0, 1);
        $this->SetFont('Arial','B',25);
        $this->Cell(0,20,utf8_decode("Merci pour ta commande ".$customer_forname." !"), 0, 1, 'C');

        $this->Cell(100, 15, '', 0, 1);
        $this->SetFont('Arial','B',20);
        $this->Cell(0,25,utf8_decode("Resumé de la commande :"), 0, 1, 'C');

        $this->SetFont('Arial','B',10);
        $this->Cell(70,5,utf8_decode("Client : $customer_forname $customer_surname"),0,1);
        $this->Cell(70,5,utf8_decode("Achat effectué le $date"),0,1);
        $this->Cell(50,5,utf8_decode("Adresse de livraison : $address_add1"),0,1);
        $this->Cell(70,5,$address_city,0,1);
        
        $this->Cell(70 ,5,utf8_decode("Type de paiement : $payment_type"),0,1);
        $this->SetFont('Arial','I',10);
        if ($payment_type == 'Chèque') {
            $this->Cell(70,5,utf8_decode("Votre chèque sera à l'ordre de 'Extra Eats Inc.' et envoyé à l'adresse suivante :"),0,1);
            $this->Cell(70,5,utf8_decode("15 Bd André Latarjet, 69100 Villeurbanne"),0,1);
        }

        $this->Cell(10, 10, '', 0, 1);
        $this->SetFont('Arial','B',15);
        $this->Cell(70 ,5,utf8_decode("Total de la commande : $total Euros"),0,1);


        $this->Output();
    }
}