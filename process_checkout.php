<?php
session_start();
include 'db.php';
require_once 'vendor/autoload.php'; 

use Twilio\Rest\Client;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($pdo === null) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        header('Location: error.php?message=' . urlencode('Database connection failed.'));
        exit();
    }
}

$stmt = $pdo->prepare("SELECT email, phone_no, home_address FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT ci.cart_item_id, ci.quantity, ci.price, p.product_id, p.name, p.productPic, p.stock
    FROM cart_items ci
    INNER JOIN carts c ON ci.cart_id = c.cart_id
    INNER JOIN products p ON ci.product_id = p.product_id
    WHERE c.user_id = :user_id
");
$stmt->execute([':user_id' => $userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalAmount = 0;
if ($cartItems) {
    foreach ($cartItems as $item) {
        $totalAmount += (float)$item['price'] * (int)$item['quantity'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, status)
            VALUES (:user_id, :total_amount, 'pending')
        ");
        $stmt->execute([ ':user_id' => $userId, ':total_amount' => $totalAmount ]);
        $orderId = $pdo->lastInsertId();

        $stmtOrderItems = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (:order_id, :product_id, :quantity, :price)
        ");
        $stmtUpdateStock = $pdo->prepare("
            UPDATE products SET stock = stock - :quantity
            WHERE product_id = :product_id AND stock >= :quantity
        ");

        foreach ($cartItems as $item) {
            $stmtCheckProduct = $pdo->prepare("
                SELECT COUNT(*) FROM products WHERE product_id = :product_id
            ");
            $stmtCheckProduct->execute([':product_id' => $item['product_id']]);
            $productExists = $stmtCheckProduct->fetchColumn();

            if (!$productExists) {
                throw new Exception("Product ID " . $item['product_id'] . " does not exist.");
            }

            $stmtUpdateStock->execute([ ':product_id' => $item['product_id'], ':quantity' => $item['quantity'] ]);
            if ($stmtUpdateStock->rowCount() === 0) {
                throw new Exception("Insufficient stock for product: " . $item['name']);
            }

            $stmtOrderItems->execute([ ':order_id' => $orderId, ':product_id' => $item['product_id'], ':quantity' => $item['quantity'], ':price' => $item['price'] ]);
        }

        $stmtRevenue = $pdo->prepare("
            INSERT INTO revenue (order_id, amount)
            VALUES (:order_id, :amount)
        ");
        $stmtRevenue->execute([ ':order_id' => $orderId, ':amount' => $totalAmount ]);

        $pdo->commit();

        $token = getenv('TWILIO_TOKEN'); 
        $twilio_number = getenv('TWILIO_NUMBER');
        
        $client = new Client($sid, $token);

        $phoneNumber = $user['phone_no'];
        if (substr($phoneNumber, 0, 1) == '0') {
            $phoneNumber = '+63' . substr($phoneNumber, 1);
        }

        $message = "Thank you for your order. Your total amount is ₱" . number_format($totalAmount, 2) . ". Payment method: " . $paymentMethod;

        try {
            $client->messages->create(
                $phoneNumber, 
                [
                    'from' => $twilio_number,
                    'body' => $message
                ]
            );
        } catch (Exception $e) {
            error_log("Error sending SMS: " . $e->getMessage());
        }

        $stmtClearCart = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1)");
        $stmtClearCart->execute([':user_id' => $userId]);

        header('Location: home.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();

        error_log($e->getMessage());
        header('Location: error.php?message=' . urlencode($e->getMessage()));
        exit();
    }
}
?>
