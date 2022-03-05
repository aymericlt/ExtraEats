<?php

if (!isset($_SESSION["logged"])) { //Si la variable de connexion n'est pas initialisée
  $_SESSION["logged"] = false;//On l'initialise à false
}

if (!isset($_SESSION["logged_as_admin"])) { //Si la variable de connexion n'est pas initialisée
  $_SESSION["logged_as_admin"] = false;//On l'initialise à false
}
?>


<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <!-- Implémentation des Fonts Google -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="assets/css/style.css" />
        <title><?= $title ?></title>
    </head>
    <body>
        <div id="global">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light">
          <div class="container">
            <a class="navbar-left" href="?page=catalog"><img src="assets/img/shopify3.png" class="nav-logo"></a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarScroll">

              <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link active" href="?page=catalog">Catalogue</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="?page=Cart">Panier</a>
                </li>
                <!--<?= var_dump($_SESSION) ?>-->
                <?php
                  if ($_SESSION["logged"]) {
                    echo '<li class="nav-item">
                            <a class="nav-link" href="?page=login">Déconnexion</a>
                          </li>';
                    if ($_SESSION["logged_as_admin"]) {
                      echo '<li class="nav-item"> 
                              <a class="nav-link" href="?page=adminPannel">Pannel admin : '. $_SESSION["username"] .'</a>
                            </li>';
                    } else {
                      echo '<li class="nav-item"> 
                              <a class="nav-link" href="?page=profile">Profil : '. $_SESSION["username"] .'</a>
                            </li>';
                    }
                  } else {
                    echo '<li class="nav-item">
                            <a class="nav-link" href="?page=login">Connexion</a>
                          </li>';
                    echo '<li class="nav-item">
                            <a class="nav-link" href="?page=register">Inscription</a>
                          </li>';
                  }
                ?>
              </ul>

              <div class="search-bar">
                <form action = "index.php?page=catalog" method="POST" class="d-flex">
                  <input class="search-zone" type="chercher" placeholder="" aria-label="Chercher" name="searchTextZone" required>
                  <button class="btn0" type="submit" name="searchItemsRequest">CHERCHER</button>
                </form>
              </div>
            </div>
          </div>
          
        </nav>
          <div id="contenu">
              <?= $content ?>
          </div> <!-- #contenu -->
            
        <footer id="contact">
            <div id="deuxiemeTrait"></div>
            <div id="copyrightEtIcons">
                <div id="copyright">
                    <span>© PURICELLI Nathan et LETO Aymeric</span>
                </div>
            <div id="icons">
                <a href="https://forge.univ-lyon1.fr/p1907453/boutique"><img src = "assets/img/git.png"></i></a>
            </div>
            </div>
        </footer>
        </div> <!-- #global -->
    </body>
</html>