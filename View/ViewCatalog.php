<?php $this->title = "Catalogue"; ?>


<div class="container catalog">
    <div class="text-center">
        <h1>Qu'est-ce qui vous ferait plaisir ?</h1>
    </div>
    <?php if($category_name['name'] == 'Toutes les categories'):?>
    <div class="categoryBox selected">
    <?php else:?>
    <div class="categoryBox">
    <?php endif;?>
        <a href="index.php?page=catalog">
            <h4>TOUS NOS PRODUITS</h4>
        </a>
    </div>

    <?php foreach ($categories as $categorie):?>
    
    <?php if($category_name['name'] == $categorie['name']):?>
    <div class="categoryBox selected">
    <?php else:?>
    <div class="categoryBox">
    <?php endif;?>
        <a href="<?= "index.php?page=catalog&cat=" . $categorie['id'] ?>">
            <h4><?= strtoupper($categorie['name']) ?></h4>
        </a>
    </div>
        
    <?php endforeach; ?>

    <?php if($txtSearched != null):?>
        <br/>
        <?php if($products->rowCount() == 0):?>
        <div class="text-center">
            <h2>Aucun arcticle ne correspond à votre recherche ' <i><?= $txtSearched ?></i> '</h2>
        </div>
        <?php else:?>
        <div class="text-center">
            <h2>Voici les articles correspondant à ' <i><?= $txtSearched ?></i> '</h2>
        </div>
        <?php endif;?>
    <?php endif;?>

    <div class="row">

    <?php foreach ($products as $product):?>

        <div class="col-lg-3 col-md-6">
            <div class="catalogBox">
                <a href="<?= "index.php?page=product&id=" . $product['id'] ?>">
                <div class="clickableBox">
                    <h3><?= $product['name'] ?></h3>
                    
                    <?php 
                    $image = $product['image'];
                    $chemin="assets/img/".$image;
                    $img = "<img src = \"" . $chemin ."\" class =\"articleImg\">";
                    echo ($img);
                    ?>
                    
                    <p><?= $product['description'] ?></p>
                    <div class="smallLine"></div>
                    <?php $price = $product['price'];
                    echo("<h5>". $price . "€ </h5>");?>
                    <div class="smallLine"></div>
                </div>
                </a>
                <?php if($product['quantity'] != 0) :?>
                <form action = "index.php?page=Cart" method="POST" id="addToCartForm" name="addToCartForm">
                    <input type='hidden' id='hiddenQuantity' name='hiddenQuantity' value='1'>
                    <?=  "<input type='hidden' id='idProduct' name='idProduct' value=".$product['id'].">" ?>
                    <input class="btn1" type="submit" name = "addedToCart" value="AJOUTER AU PANIER">
                </form>
                <?php else: ?>
                    <br/>
                    <h5 class="unavailableProductText">Article en rupture de stock</h5>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; ?>

    </div>
</div>