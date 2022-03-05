<?php


abstract class Model {

    private $bdd;

    protected function executerRequete($sql, $params = null) {
        if ($params == null) {
            $resultat = $this->getBdd()->query($sql); // exécution directe
        }
        else {
            $resultat = $this->getBdd()->prepare($sql);  // requête préparée
            $resultat->execute($params);
        }
        return $resultat;
    }

    private function getBdd() {
        if ($this->bdd == null) {
            $dbhost = 'localhost';
            $dbbase = 'web4shop';
            $dbuser = 'userepul';
            $dbpwd = 'epul';
            // Création de la connexion
            $this->bdd = new PDO("mysql:host=$dbhost;dbname=$dbbase;charset=utf8",$dbuser, $dbpwd,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        return $this->bdd;
    }

    public function getLastInsertId()
    {
        return $this->getBdd()->lastInsertId();
    }

}