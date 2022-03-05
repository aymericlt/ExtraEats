<?php $this->title = "Inscription"; ?>

<div class="container">
    <div class="text-center">
        <h1>Inscription</h1>
    </div>
    <div class="containerLogin">
        <form action="index.php?page=register" method='post'>
            <?php 
            if ($errorMessage != "") {
                echo "<p class='errMsg'>".$errorMessage."</p>";
            } 
            ?>
            <div class="text-center">
                <h3>Informations globales</h3>
            </div>
            <div class="form-group">
                <label for="inputUsername_register">Nom d'utilisateur</label>
                <input type="text" class="form-control" name="register_form_username" placeholder="Votre nom d'utilisateur">
            </div>
            <div class="form-group">
                <label for="inputPassword_register">Mot de passe</label>
                <input type="password" class="form-control" name="register_form_password" placeholder="Votre mot de passe">
            </div>
            <div class="form-group">
                <label for="inputPasswordConfirmation_register">Confirmer votre mot de passe</label>
                <input type="password" class="form-control" name="register_form_password_confirmation" placeholder="Confirmation">
            </div>
            <br/>
            <div class="text-center">
                <h3>Informations personnelles</h3>
            </div>
            <!--
            (`id`, `firstname`, `surname`, `add1`, `add2`, `city`, `postcode`, `phone`, `email`, `registered`)
            -->
            <div class="row">
                <div class="col">
                    <label for="inputFirstName">Prénom</label>
                    <input type="text" class="form-control" name="register_form_firstname" placeholder="Jean" required>
                </div>
                <div class="col">
                    <label for="inputSurname">Nom</label>
                    <input type="text" class="form-control" name="register_form_surname" placeholder="De la Fontaine" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="inputAdd1">Première adresse</label>
                <input type="text" class="form-control" name="register_form_add1" placeholder="" required>
            </div>
            <div class="form-group">
                <label for="inputAdd2">Seconde adresse</label>
                <input type="text" class="form-control" name="register_form_add2" placeholder="">
            </div>

            <div class="row">
                <div class="col">
                    <label for="inputCity">Ville</label>
                    <input type="text" class="form-control" name="register_form_city" placeholder="Ex : Villeurbanne" required>
                </div>
                <div class="col">
                    <label for="inputPostCode">Code Postal</label>
                    <input type="text" class="form-control" name="register_form_postcode" placeholder="Ex : 69100" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="inputPhone">Numéro de téléphone</label>
                    <input type="text" class="form-control" name="register_form_phone" placeholder="Ex : 06 12 34 56 78" required>
                </div>
                <div class="col">
                    <label for="inputEMail">Adresse e-mail</label>
                    <input type="text" class="form-control" name="register_form_email" placeholder="nom@exemple.com" required>
                </div>
            </div>
            <div class="buttonHolder">
                <button class="btn1" type="submit" name='register-request'>S'INSCRIRE</button>
            </div>
        </form>
    </div>
</div>