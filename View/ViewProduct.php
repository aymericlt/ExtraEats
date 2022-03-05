<?php $this->title = $product['name']." | Infos"; ?>




<!-- PARTIE PRODUIT -->

<div class="container">
    <div class="row productBox">
        <div class="col-md-8 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-sm-6 productImgSection">
                    <?php 
                    $image = $product['image'];
                    $chemin="assets/img/".$image;
                    $img = "<img src = \"" . $chemin ."\" class =\"productImg\">";
                    echo ($img);
                    ?>
                </div>
                <div class="col-md-12 col-sm-6 productDescription">
                    <h5>Description :</h5>
                    <?= $product['description'] ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 productBuyingSection">
                <h3><?= $product['name'] ?></h3>
                <h5>Prix : <?= $product['price'] ?>€</h5>
                <p>Quantité disponible : <span class="quantityAvailable"><?= $product['quantity'] ?></span></p>
                <?php if ($product['quantity'] != 0):?>
                    <p>Combien en voulez-vous ?</p>
                    <p>
                        <button class="btn2 counterMinus" type="submit">-</button>
                        <span class="counterSection">1</span>
                        <button class="btn2 counterPlus" type="submit">+</button>
                    </p>
                    <form action = "index.php?page=Cart" method="POST" id="addToCartForm" name="addToCartForm">
                        <input type='hidden' id='hiddenQuantity' name='hiddenQuantity' value='1'>
                        <?=  "<input type='hidden' id='idProduct' name='idProduct' value=".$product['id'].">" ?>
                        <input class="btn1" type="submit" name = "addedToCart" value="AJOUTER AU PANIER">
                    </form>
                <?php else: ?>
                    <h5 class="unavailableProductText">Article en rupture de stock</h5>
                <?php endif; ?>
        </div>
    </div>

</div>

<!--  PARTIE AVIS   -->

<div class="container">
    <div class="text-center">
        <h2>Avis des consommateurs sur ce produit</h2>
    </div>
    <div class="container">

        <?php foreach ($reviews as $review):?>
        <div class="row reviewBox">
            <div class="col-1 reviewUserPhoto">
            <?php 
            $image = $review['photo_user'];
            $path="assets/img/".$image;
            $img = "<img src = \"" . $path ."\" class =\"reviewImg\">";
            echo ($img);
            ?>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-2 reviewUserName"><h5><?= $review['name_user'] ?></h5></div>
                    <?php
                    for ($i=1; $i<=5; $i++) {
                        echo "<div class='col-1 reviewImgSection'>";
                        if ($i <= $review['stars']) {
                            echo "<img src='assets/img/review_star.png' class ='reviewStars'>";
                        } else {
                            echo "<img src='assets/img/review_gray.png' class ='reviewStars'>";
                        }
                        echo "</div>";
                    }
                    ?>
                    <div class="col titleSection"><?= "|   ".$review['title'] ?></div>
                </div>
                <div class="row">
                    <div class="col reviewDescription"><?= $review['description'] ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>


    <div class="text-center">
        <h2>Ajouter un commentaire</h2>
    </div>
    <div class="container">
        <div class="addReviewSection">
            <?php 
            echo ("<form class='formAddReview' action=\"index.php?page=product&id=".$product['id']." \" method='POST'"); 
            ?>
            
            <label for="review_form_name_user">Nom</label>
            <input type="text" class="review_form_name_user" name="review_form_name_user" required>

            <input type="radio" name = "review_form_photo_user" id = "review_form_photo_user_male" value="homme.jpg" checked>
            <label for="review_form_photo_user_male">Homme</label>
            <input type="radio" name = "review_form_photo_user" id = "review_form_photo_user_female" value="femme.png">
            <label for="review_form_photo_user_female">Femme</label>

            
            <br/>
            <input type="radio" name = "review_form_stars" id = "review_form_stars_0" value="0" checked>
            <label for="review_form_stars_male">0</label>
            <input type="radio" name = "review_form_stars" id = "review_form_stars_1" value="1">
            <label for="review_form_stars_male">1</label>
            <input type="radio" name = "review_form_stars" id = "review_form_stars_2" value="2">
            <label for="review_form_stars_male">2</label>
            <input type="radio" name = "review_form_stars" id = "review_form_stars_3" value="3">
            <label for="review_form_stars_male">3</label>
            <input type="radio" name = "review_form_stars" id = "review_form_stars_4" value="4">
            <label for="review_form_stars_male">4</label>
            <input type="radio" name = "review_form_stars" id = "review_form_stars_5" value="5">
            <label for="review_form_stars_male">5</label>

            <br/>
            <label for='form_name_title'>Titre :</label>
            <input type='text' id = 'review_form_title' name = 'review_form_title' required/>

            <br/>
            <label for='review_form_description'>Description :</label>
            <textarea name='review_form_description' id='review_form_description' rows='4' cols='50' required style="resize: none;"></textarea>

            <br/>
            <div class="buttonHolder">
                <button class="btn1" type="submit" name='confirm-review'>Ajouter un commentaire</button>
            </div>
            </form>
        </div>
    </div>

</div>

<script type="text/javascript" src="assets/js/productCounter.js"></script>