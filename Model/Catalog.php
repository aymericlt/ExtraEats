<?php
require_once 'Model.php';
class Catalog extends Model {
    public function getProducts($cat_id, $txtSearched) {
        if ($txtSearched != null) {
            $sql = "SELECT * FROM products WHERE name LIKE ?";
            $products = $this->executerRequete($sql, array('%'.$txtSearched.'%'));
            return $products;
        } else {
            if($cat_id != 0) {
                $sql = "SELECT * FROM products WHERE cat_id = ?";
                $products = $this->executerRequete($sql, array($cat_id));
                
                if ($products->rowCount() > 0) {
                    return $products;
                }
                else {
                    throw new Exception("Aucun produit ne correspond à l'identifiant '$cat_id'");
                }
            }
            else {
                $sql = "SELECT * FROM products";
                $products = $this->executerRequete($sql);
                return $products;
            }
        }
    }

    public function getCategories() {
        $sql = "SELECT * FROM categories";
        $categories = $this->executerRequete($sql);
        return $categories;
    } 

    public function getCategoryName($id) {
        if ($id == 0) {
            $categ = array('name' => 'Toutes les categories');
            return $categ;
        }
        else {
            $sql = "SELECT name FROM categories where id = ?";
            $categ = $this->executerRequete($sql, array($id));
            if ($categ->rowCount() > 0) {
                return $categ->fetch();
            }
            else
                throw new Exception("Aucune catégorie ne correspond à l'identifiant '$id'");
        }
    } 
};