<?php
session_start();
include 'db.php';

if (!isset($_GET['order_id'])) {
    echo "Order not found.";
    exit();
}

$orderId = $_GET['order_id'];

$stmt = $pdo->prepare("SELECT o.*, oi.product_id, oi.quantity, oi.price, p.name FROM orders o 
                        JOIN order_items oi ON o.order_id = oi.order_id
                        JOIN products p ON oi.product_id = p.product_id
                        WHERE o.order_id = :order_id");
$stmt->execute([':order_id' => $orderId]);
$order = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}

$totalAmount = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h2>Order Confirmation</h2>
        <p>Thank you for your order! Below are your order details:</p>

        <h5>Order Summary</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>₱<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                    </tr>
                    <?php $totalAmount += $item['quantity'] * $item['price']; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h5>Total: ₱<?= number_format($totalAmount, 2) ?></h5>
        <p>Your order is being processed and will be shipped soon.</p>
        <a href="home.php" class="btn btn-primary">Go to Home</a>
    </div>
</body>