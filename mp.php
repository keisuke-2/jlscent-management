<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

function fetchOrdersByStatus($pdo, $userId, $status) {
    $stmt = $pdo->prepare("
        SELECT o.order_id, o.total_amount, o.status, oi.quantity, oi.price, p.name, p.productPic
        FROM orders o
        INNER JOIN order_items oi ON o.order_id = oi.order_id
        INNER JOIN products p ON oi.product_id = p.product_id
        WHERE o.user_id = :user_id AND o.status = :status
    ");
    $stmt->execute([':user_id' => $userId, ':status' => $status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$toShipOrders = fetchOrdersByStatus($pdo, $userId, 'pending');
$toReceiveOrders = fetchOrdersByStatus($pdo, $userId, 'shipped');
$completedOrders = fetchOrdersByStatus($pdo, $userId, 'completed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JL Scent | My Purchases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mp.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container">
            <a class="navbar-brand text-light" href="#">JL Scent</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="home.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="py-5 text-center text-light">
        <h1 class="display-4">My Purchases</h1>
        <p>Track and manage your orders easily</p>
    </header>

    <section class="py-5">
        <div class="container">
            <ul class="nav nav-tabs mb-4" id="purchasesTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="toShip-tab" data-bs-toggle="tab" data-bs-target="#toShip" role="tab">To Ship</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="toReceive-tab" data-bs-toggle="tab" data-bs-target="#toReceive" role="tab">To Receive</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" role="tab">Completed</button>
                </li>
            </ul>
            <div class="tab-content" id="purchasesTabContent">
                <div class="tab-pane fade show active" id="toShip" role="tabpanel">
                    <?php if ($toShipOrders): ?>
                        <?php foreach ($toShipOrders as $order): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5><?= htmlspecialchars($order['name']) ?></h5>
                                    <p>Quantity: <?= htmlspecialchars($order['quantity']) ?></p>
                                    <p>Price: ₱<?= number_format($order['price'], 2) ?></p>
                                    <p>Status: <?= htmlspecialchars($order['status']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No orders to ship.</p>
                    <?php endif; ?>
                </div>
                <div class="tab-pane fade" id="toReceive" role="tabpanel">
                    <?php if ($toReceiveOrders): ?>
                        <?php foreach ($toReceiveOrders as $order): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5><?= htmlspecialchars($order['name']) ?></h5>
                                    <p>Quantity: <?= htmlspecialchars($order['quantity']) ?></p>
                                    <p>Price: ₱<?= number_format($order['price'], 2) ?></p>
                                    <p>Status: <?= htmlspecialchars($order['status']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No orders to receive.</p>
                    <?php endif; ?>
                </div>
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    <?php if ($completedOrders): ?>
                        <?php foreach ($completedOrders as $order): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5><?= htmlspecialchars($order['name']) ?></h5>
                                    <p>Quantity: <?= htmlspecialchars($order['quantity']) ?></p>
                                    <p>Price: ₱<?= number_format($order['price'], 2) ?></p>
                                    <p>Status: <?= htmlspecialchars($order['status']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No completed orders.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
