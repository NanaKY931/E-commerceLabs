<?php
require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController {

    private $model;

    public function __construct() {
        $this->model = new CustomerClass();
    }

    /**
     * Fetch a single customer row by email (used after registration to build session).
     */
    public function getCustomerByEmail($email) {
        return $this->model->getCustomerByEmail($email);
    }

    /**
     * Register a new customer.
     *
     * Checks for duplicate email, hashes the password,
     * then delegates the INSERT to the model.
     *
     * @return array ['success' => true] | ['success' => false, 'error' => string]
     */
    public function register($name, $email, $pass, $country, $city, $contact) {
        if ($this->model->emailExists($email)) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
        $inserted   = $this->model->addCustomer($name, $email, $hashedPass, $country, $city, $contact);
        if ($inserted) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Registration failed. Please try again.'];
    }

    /**
     * Log in a customer.
     *
     * Delegates credential verification to the model.
     *
     * @return array ['success' => true, 'customer' => row] | ['success' => false, 'error' => string]
     */
    public function login($email, $pass) {
        $customer = $this->model->login($email, $pass);
        if ($customer) {
            return ['success' => true, 'customer' => $customer];
        }
        return ['success' => false, 'error' => 'Invalid email or password.'];
    }
}
?>
