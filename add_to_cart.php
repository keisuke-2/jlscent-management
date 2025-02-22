<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $userId = $_SESSION['user_id'] ?? null; 

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id");
        $stmt->execute([':product_id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product && $product['stock'] > 0) {
            $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1");
            $stmt->execute([':user_id' => $userId]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cart) {
                $stmt = $pdo->prepare("INSERT INTO carts (user_id, session_id, created_at, updated_at) VALUES (:user_id, :session_id, NOW(), NOW())");
                $stmt->execute([
                    ':user_id' => $userId,
                    ':session_id' => session_id() 
                ]);
                $cartId = $pdo->lastInsertId(); 
            } else {
                $cartId = $cart['cart_id'];
            }

            $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id");
            $stmt->execute([':cart_id' => $cartId, ':product_id' => $productId]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                $newQuantity = $existingItem['quantity'] + 1;
                $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id");
                $stmt->execute([':quantity' => $newQuantity, ':cart_item_id' => $existingItem['cart_item_id']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (:cart_id, :product_id, 1, :price)");
                $stmt->execute([
                    ':cart_id' => $cartId,
                    ':product_id' => $productId,
                    ':price' => $product['price'] 
                ]);
            }

            echo json_encode(['success' => true, 'message' => 'Product added to cart successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not available or out of stock.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
