<?php
require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController {

    private $model;

    public function __construct() {
        $this->model = new ProductClass();
    }

    // ---------- BRANDS ----------

    public function addBrand($name) {
        return $this->model->addBrand($name);
    }

    public function getAllBrands() {
        return $this->model->getAllBrands();
    }

    public function getBrandById($id) {
        return $this->model->getBrandById($id);
    }

    public function updateBrand($id, $name) {
        return $this->model->updateBrand($id, $name);
    }

    // ---------- CATEGORIES ----------

    public function addCategory($name) {
        return $this->model->addCategory($name);
    }

    public function getAllCategories() {
        return $this->model->getAllCategories();
    }

    public function getCategoryById($id) {
        return $this->model->getCategoryById($id);
    }

    public function updateCategory($id, $name) {
        return $this->model->updateCategory($id, $name);
    }
}
?>
