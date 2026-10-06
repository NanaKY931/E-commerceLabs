<?php
require_once __DIR__ . '/../classes/CartClass.php';

class CartController {

    private $model;

    public function __construct() {
        $this->model = new CartClass();
    }

    public function isInCart($product_id, $ip) {
        return $this->model->isInCart($product_id, $ip);
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null) {
        return $this->model->addToCart($product_id, $qty, $ip, $customer_id);
    }

    public function getCartItems($ip) {
        return $this->model->getCartItems($ip);
    }

    public function getCartCount($ip) {
        return $this->model->getCartCount($ip);
    }

    public function getCartTotal($ip) {
        return $this->model->getCartTotal($ip);
    }

    public function removeFromCart($product_id, $ip) {
        return $this->model->removeFromCart($product_id, $ip);
    }

    public function updateQty($product_id, $new_qty, $ip) {
        return $this->model->updateQty($product_id, $new_qty, $ip);
    }

    public function processPayment($customer_id, $ip, $reference, $amount) {
        return $this->model->processPayment($customer_id, $ip, $reference, $amount);
    }

    public function getOrderById($order_id) {
        return $this->model->getOrderById($order_id);
    }
}
?>
