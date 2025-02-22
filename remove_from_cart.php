<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];
$cartItemId = $_POST['cart_item_id'] ?? null;

if ($cartItemId) {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = :cart_item_id AND cart_id IN (SELECT cart_id FROM carts WHERE user_id = :user_id)");
    $stmt->execute([':cart_item_id' => $cartItemId, ':user_id' => $userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove item']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid cart item ID']);
}
?>
