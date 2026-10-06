<?php
require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database {

    public function isInCart($product_id, $ip) {
        $stmt = $this->conn->prepare("SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ?");
        if (!$stmt) return false;
        $stmt->bind_param("is", $product_id, $ip);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null) {
        $stmt = $this->conn->prepare("INSERT INTO cart (p_id, ip_add, qty, c_id) VALUES (?, ?, ?, ?)");
        if (!$stmt) return false;
        $stmt->bind_param("isii", $product_id, $ip, $qty, $customer_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getCartItems($ip) {
        $sql = "SELECT c.*, p.product_title, p.product_price, p.product_image 
                FROM cart c 
                JOIN products p ON c.p_id = p.product_id 
                WHERE c.ip_add = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $stmt->close();
        return $items;
    }

    public function getCartCount($ip) {
        $sql = "SELECT COUNT(*) as count FROM cart WHERE ip_add = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return 0;
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] ?? 0;
    }

    public function getCartTotal($ip) {
        $items = $this->getCartItems($ip);
        $total = 0;
        foreach ($items as $item) {
            $total += ($item['qty'] * $item['product_price']);
        }
        return $total;
    }

    public function removeFromCart($product_id, $ip) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE p_id = ? AND ip_add = ?");
        if (!$stmt) return false;
        $stmt->bind_param("is", $product_id, $ip);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function updateQty($product_id, $new_qty, $ip) {
        if ($new_qty < 1) return false;
        $stmt = $this->conn->prepare("UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?");
        if (!$stmt) return false;
        $stmt->bind_param("iis", $new_qty, $product_id, $ip);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // ---------- ORDERS ----------

    public function processPayment($customer_id, $ip, $reference, $amount) {
        $this->conn->begin_transaction();
        try {
            $invoice_no = mt_rand(100000, 999999);
            $order_date = date('Y-m-d H:i:s');
            $status = 'paid';
            
            $stmt = $this->conn->prepare("INSERT INTO orders (customer_id, invoice_no, order_date, order_status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $customer_id, $invoice_no, $order_date, $status);
            $stmt->execute();
            $order_id = $stmt->insert_id;
            $stmt->close();

            $cartItems = $this->getCartItems($ip);
            $stmt = $this->conn->prepare("INSERT INTO orderdetails (order_id, product_id, qty) VALUES (?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmt->bind_param("iii", $order_id, $item['p_id'], $item['qty']);
                $stmt->execute();
            }
            $stmt->close();

            $stmt = $this->conn->prepare("DELETE FROM cart WHERE ip_add = ?");
            $stmt->bind_param("s", $ip);
            $stmt->execute();
            $stmt->close();

            $this->conn->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function getOrderById($order_id) {
        $sql = "SELECT o.*, od.qty, p.product_title, p.product_price 
                FROM orders o
                JOIN orderdetails od ON o.order_id = od.order_id
                JOIN products p ON od.product_id = p.product_id
                WHERE o.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = ['details' => [], 'items' => []];
        $first = true;
        while ($row = $result->fetch_assoc()) {
            if ($first) {
                $order['details'] = [
                    'order_id' => $row['order_id'],
                    'invoice_no' => $row['invoice_no'],
                    'order_date' => $row['order_date'],
                    'order_status' => $row['order_status'],
                    'customer_id' => $row['customer_id']
                ];
                $first = false;
            }
            $order['items'][] = [
                'product_title' => $row['product_title'],
                'product_price' => $row['product_price'],
                'qty' => $row['qty']
            ];
        }
        $stmt->close();
        return empty($order['details']) ? false : $order;
    }
}
?>
