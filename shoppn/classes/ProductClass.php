<?php
require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database {

    // ---------- BRANDS ----------

    /**
     * Add a new brand
     */
    public function addBrand($name) {
        $stmt = $this->conn->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        if (!$stmt) return false;
        $stmt->bind_param("s", $name);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Get all brands ordered by name ASC
     */
    public function getAllBrands() {
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";
        $result = $this->conn->query($sql);
        $brands = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
        }
        return $brands;
    }

    /**
     * Get brand by ID
     */
    public function getBrandById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM brands WHERE brand_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    /**
     * Update a brand
     */
    public function updateBrand($id, $name) {
        $stmt = $this->conn->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("si", $name, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // ---------- CATEGORIES ----------

    /**
     * Add a new category
     */
    public function addCategory($name) {
        $stmt = $this->conn->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        if (!$stmt) return false;
        $stmt->bind_param("s", $name);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Get all categories ordered by name ASC
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        $result = $this->conn->query($sql);
        $categories = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }

    /**
     * Get category by ID
     */
    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE cat_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    /**
     * Update a category
     */
    public function updateCategory($id, $name) {
        $stmt = $this->conn->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("si", $name, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
?>
