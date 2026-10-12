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

    // ---------- PRODUCTS ----------

    /**
     * Add a new product
     * @param int    $cat       category ID (FK)
     * @param int    $brand     brand ID (FK)
     * @param string $title     product title
     * @param float  $price     product price
     * @param string $desc      product description
     * @param string $image     filename of uploaded image (stored in images/products/)
     * @param string $keywords  comma-separated keywords
     */
    public function addProduct($cat, $brand, $title, $price, $desc, $image, $keywords) {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;
        $stmt->bind_param("iisdsss", $cat, $brand, $title, $price, $desc, $image, $keywords);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Update an existing product
     * If no new image was uploaded, pass the existing filename back as $image.
     */
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image, $keywords) {
        $stmt = $this->conn->prepare(
            "UPDATE products
             SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?,
                 product_desc = ?, product_image = ?, product_keywords = ?
             WHERE product_id = ?"
        );
        if (!$stmt) return false;
        $stmt->bind_param("iisdsssi", $cat, $brand, $title, $price, $desc, $image, $keywords, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Get a single product by ID — JOINs categories and brands so the view
     * receives cat_name and brand_name without extra queries.
     */
    public function getProductById($id) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.cat_name, b.brand_name
             FROM products p
             JOIN categories c ON p.product_cat  = c.cat_id
             JOIN brands     b ON p.product_brand = b.brand_id
             WHERE p.product_id = ?"
        );
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    /**
     * Get all products with their category and brand names (for admin list)
     */
    public function getAllProducts() {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                JOIN categories c ON p.product_cat  = c.cat_id
                JOIN brands     b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";
        $result   = $this->conn->query($sql);
        $products = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }
}
?>
