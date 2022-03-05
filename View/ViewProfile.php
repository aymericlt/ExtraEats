<?php $this->title = "Profil"; ?>

<div class="container">
    <div class="container">
        <div class="text-center">
            <h2>Liste des commandes passées</h2>
        </div>

        <!-- AFFICHAGE DES COMMANDES -->
        <?php foreach ($ordersList as $order):?>
        <?php if($order['status'] == 10):?>
        <div class="orderBox confirmed">
        <?php else:?>
        <div class="orderBox">
        <?php endif;?>
            <div class="row">
                <div class="col-5" style="text-align:left;">
                    <h5>COMMANDE #<?= $order['id'] ?></h5>
                    <p><?= $order['date'] ?><br/>
                    <?= $order['total'] ?> €
                    <?php if($order['payment_type'] != null): ?>
                    (Par <?= $order['payment_type'] ?>)
                    <?php endif; ?></p>
                </div>
                <div class="col-3">
                    <p>Client : <?= $order['customer']['forname']." ".$order['customer']['surname'] ?></p>
                </div>
                <div class="col-4" style="text-align:right;">
                    <?php if($order['address'] != null):?>
                    <p>Adresse : <?= $order['address']['add1'] ?> <br/>
                    <?= $order['address']['city'] ?> <br/>
                    <?= $order['address']['postcode'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <h5>Détails commande :</h5>
            </div>
            <div class="row">
                <?php foreach ($order['itemList'] as $item):?>
                <div class="smallProductBox">
                    <?= "<img src = \"assets/img/". $item['image'] ."\" class ='smallItemImage'>" ?>
                    <p>x<?= $item['quantity'] ?><br/>
                    <?= $item['price']*$item['quantity'] ?> €</p>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col">
                    <?php
                    switch ($order['status']) {
                        case 1:
                            $txtStatus = "Finalisée, prête à être payée";
                            break;
                        case 2:
                            $txtStatus = "Commande payée";
                            break;
                        case 10:
                            $txtStatus = "Commande confirmée et envoyée";
                            break;
                    }
                    ?>
                    <h5>Etat de la commande : <?= $txtStatus ?></h5>
                </div>
                <?php if($order['status'] == 10 || $order['status'] == 2):?>
                <div class="col">
                    <form action = "index.php?page=profile" target="_blank" method="POST" class="text-center">
                        <?=  "<input type='hidden' name='PDF_customer_forname' value='".$order['customer']['forname']."'>" ?>
                        <?=  "<input type='hidden' name='PDF_customer_surname' value='".$order['customer']['surname']."'>" ?>
                        <?=  "<input type='hidden' name='PDF_address_add1' value='".$order['address']['add1']."'>" ?>
                        <?=  "<input type='hidden' name='PDF_address_city' value='".$order['address']['city']."'>" ?>
                        <?=  "<input type='hidden' name='PDF_address_postcode' value='".$order['address']['postcode']."'>" ?>
                        <?=  "<input type='hidden' name='PDF_payment_type' value='".$order['payment_type']."'>" ?>
                        <?=  "<input type='hidden' name='PDF_date' value='".$order['date']."'>" ?>
                        <?=  "<input type='hidden' name='PDF_total' value=".$order['total'].">" ?>

                        <button class="btn1" type="submit" name='createPDF'>OBTENIR LA FACTURE</button>
                    </form>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>