<?php
require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database {

    // ---------- SELECT ----------

    /**
     * Check if an email already exists in the customer table.
     * Returns true if found, false otherwise.
     */
    public function emailExists($email) {
        $stmt = $this->conn->prepare(
            "SELECT customer_email FROM customer WHERE customer_email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Fetch a full customer row by email.
     * Returns the associative array row, or false if not found.
     */
    public function getCustomerByEmail($email) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM customer WHERE customer_email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    // ---------- INSERT ----------

    /**
     * Insert a new customer row.
     * Password must already be hashed before calling this method.
     * Returns true on success, false on failure.
     */
    public function addCustomer($name, $email, $hashedPass, $country, $city, $contact) {
        $stmt = $this->conn->prepare(
            "INSERT INTO customer
             (customer_name, customer_email, customer_pass, customer_country,
              customer_city, customer_contact, user_role)
             VALUES (?, ?, ?, ?, ?, ?, 0)"
        );
        $stmt->bind_param("ssssss", $name, $email, $hashedPass, $country, $city, $contact);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // ---------- LOGIN ----------

    /**
     * Verify credentials.
     * Fetches the customer row by email, then verifies the password hash.
     * Returns the customer row array on success, false on failure.
     */
    public function login($email, $pass) {
        $row = $this->getCustomerByEmail($email);
        if (!$row) {
            return false;
        }
        if (!password_verify($pass, $row['customer_pass'])) {
            return false;
        }
        return $row;
    }
}
?>
