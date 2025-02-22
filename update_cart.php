<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];
$productId = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if ($productId && $quantity) {
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE product_id = :product_id");
    $stmt->execute([':product_id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $product['stock'] >= $quantity) {
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id AND cart_id IN (SELECT cart_id FROM carts WHERE user_id = :user_id)");
        $stmt->execute([':quantity' => $quantity, ':product_id' => $productId, ':user_id' => $userId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update cart']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Not enough stock']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
