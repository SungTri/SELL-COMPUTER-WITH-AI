<?php
class CartModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCartByCustomerId($customerId) {
        $this->db->query("SELECT * FROM carts WHERE customer_id = :customer_id");
        $this->db->bind(':customer_id', $customerId);
        $cart = $this->db->single();

        if (!$cart) {
            // Create cart if not exists
            $this->db->query("INSERT INTO carts (customer_id) VALUES (:customer_id)");
            $this->db->bind(':customer_id', $customerId);
            $this->db->execute();
            $cartId = $this->db->lastInsertId();
            return ['id' => $cartId, 'customer_id' => $customerId];
        }

        return $cart;
    }

    public function getItems($cartId) {
        $this->db->query("
            SELECT ci.*, p.name, 
                   COALESCE(pv.price, p.price) as price, 
                   COALESCE(pv.image, p.main_image) as image, 
                   p.short_description as specs,
                   (
                       SELECT GROUP_CONCAT(CONCAT(pvo.attribute_name, ': ', pvo.attribute_value) SEPARATOR ', ') 
                       FROM product_variant_options pvo 
                       WHERE pvo.variant_id = ci.variant_id
                   ) as variant_name
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            LEFT JOIN product_variants pv ON ci.variant_id = pv.id
            WHERE ci.cart_id = :cart_id
        ");
        $this->db->bind(':cart_id', $cartId);
        return $this->db->resultSet();
    }

    public function addItem($cartId, $productId, $quantity, $variantId = null) {
        if ($variantId) {
            $this->db->query("SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id AND variant_id = :variant_id");
            $this->db->bind(':variant_id', $variantId);
        } else {
            $this->db->query("SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id AND variant_id IS NULL");
        }
        $this->db->bind(':cart_id', $cartId);
        $this->db->bind(':product_id', $productId);
        $existing = $this->db->single();

        if ($existing) {
            $newQuantity = $existing['quantity'] + $quantity;
            $this->db->query("UPDATE cart_items SET quantity = :quantity WHERE id = :id");
            $this->db->bind(':quantity', $newQuantity);
            $this->db->bind(':id', $existing['id']);
        } else {
            $this->db->query("INSERT INTO cart_items (cart_id, product_id, variant_id, quantity) VALUES (:cart_id, :product_id, :variant_id, :quantity)");
            $this->db->bind(':cart_id', $cartId);
            $this->db->bind(':product_id', $productId);
            $this->db->bind(':variant_id', $variantId);
            $this->db->bind(':quantity', $quantity);
        }
        return $this->db->execute();
    }

    public function updateQuantity($cartId, $productId, $quantity, $variantId = null) {
        if ($variantId) {
            $this->db->query("UPDATE cart_items SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id AND variant_id = :variant_id");
            $this->db->bind(':variant_id', $variantId);
        } else {
            $this->db->query("UPDATE cart_items SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id AND variant_id IS NULL");
        }
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':cart_id', $cartId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function removeItem($cartId, $productId, $variantId = null) {
        if ($variantId) {
            $this->db->query("DELETE FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id AND variant_id = :variant_id");
            $this->db->bind(':variant_id', $variantId);
        } else {
            $this->db->query("DELETE FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id AND variant_id IS NULL");
        }
        $this->db->bind(':cart_id', $cartId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function clearCart($cartId) {
        $this->db->query("DELETE FROM cart_items WHERE cart_id = :cart_id");
        $this->db->bind(':cart_id', $cartId);
        return $this->db->execute();
    }
}
