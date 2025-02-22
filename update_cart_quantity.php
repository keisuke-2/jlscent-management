<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo 'not_logged_in';
        exit();
    }

    $userId = $_SESSION['user_id'];
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    try {
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE product_id = :product_id");
        $stmt->execute([':product_id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product || $product['stock'] < $quantity) {
            echo 'insufficient_stock';
            exit();
        }

        $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
            $stmt->execute([':user_id' => $userId]);
            $cartId = $pdo->lastInsertId();
        } else {
            $cartId = $cart['cart_id'];
        }

        $stmt = $pdo->prepare("
            SELECT cart_item_id, quantity 
            FROM cart_items 
            WHERE cart_id = :cart_id AND product_id = :product_id
        ");
        $stmt->execute([':cart_id' => $cartId, ':product_id' => $productId]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cartItem) {
            $newQuantity = $cartItem['quantity'] + $quantity;
            if ($newQuantity > $product['stock']) {
                echo 'insufficient_stock';
                exit();
            }

            $stmt = $pdo->prepare("
                UPDATE cart_items 
                SET quantity = :quantity 
                WHERE cart_item_id = :cart_item_id
            ");
            $stmt->execute([':quantity' => $newQuantity, ':cart_item_id' => $cartItem['cart_item_id']]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO cart_items (cart_id, product_id, quantity, price) 
                VALUES (:cart_id, :product_id, :quantity, 
                (SELECT price FROM products WHERE product_id = :product_id))
            ");
            $stmt->execute([
                ':cart_id' => $cartId,
                ':product_id' => $productId,
                ':quantity' => $quantity
            ]);
        }

        $stmt = $pdo->prepare("
            UPDATE products 
            SET stock = stock - :quantity 
            WHERE product_id = :product_id
        ");
        $stmt->execute([':quantity' => $quantity, ':product_id' => $productId]);

        echo 'success';
    } catch (PDOException $e) {
        echo 'error';
    }
} else {
    echo 'invalid_request';
}
?>
