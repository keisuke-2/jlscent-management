<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT email, phone_no, home_address FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$cartItems = isset($_SESSION['cart'][$userId]) ? $_SESSION['cart'][$userId] : [];

$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}

if (empty($cartItems)) {
    $_SESSION['error_message'] = "Your cart is empty. Please add items to proceed to checkout.";
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JL Scent | Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">JL Scent</a>
        </div>
    </nav>

    <header class="text-light py-5 text-center">
        <div class="container">
            <h4 class="display-4">Checkout</h4>
            <p class="lead">Review and confirm your order</p>
        </div>
    </header>

    <section class="py-5 text-dark">
        <div class="container">
            <h5>Order Summary</h5>
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <img src="uploads/<?= htmlspecialchars($item['productPic']) ?>" alt="Product Image" class="img-fluid" style="width: 100px;">
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h5 class="mt-4">Customer Information</h5>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone Number:</strong> <?= htmlspecialchars($user['phone_no']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['home_address']) ?></p>

            <h5 class="mt-4">Payment Method</h5>
            <h5 class="mt-4">Payment Method</h5>
            <form id="checkoutForm" action="process_checkout.php" method="POST">
                <input type="hidden" name="total_amount" value="<?= $totalAmount ?>">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="gcash" value="GCash" required>
                    <label class="form-check-label" for="gcash">Pay via GCash</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="Cash on Delivery" required>
                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="home.php" class="btn btn-outline-danger">Cancel</a>
                    <button type="button" class="btn btn-success" id="placeOrderBtn">Place Order</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.getElementById('placeOrderBtn').addEventListener('click', function() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            if (!selectedPayment) {
                alert('Please select a payment method.');
                return;
            }

            if (selectedPayment.value === 'GCash') {
                window.location.href = 'https://m.gcash.com/gcash-login-web/index.html#/';
            } else if (selectedPayment.value === 'Cash on Delivery') {
                const checkoutForm = document.getElementById('checkoutForm');
                checkoutForm.submit();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>