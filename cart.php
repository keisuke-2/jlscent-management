<?php
session_start();
include 'db.php';
$totalAmount = 0;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT ci.cart_item_id, ci.quantity, ci.price, p.product_id, p.name, p.productPic, p.stock
    FROM cart_items ci
    INNER JOIN products p ON ci.product_id = p.product_id
    WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id LIMIT 1)
");
$stmt->execute([':user_id' => $userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$_SESSION['cart'][$userId] = [];

$totalAmount = 0;
foreach ($cartItems as &$item) {
    $item['total_price'] = $item['price'] * $item['quantity'];
    $totalAmount += $item['total_price'];
    $_SESSION['cart'][$userId][] = $item;
}
unset($item);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JL Scent | Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/cart.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">JL Scent</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="text-light py-5 text-center">
        <div class="container">
            <h4 class="display-4">Your Shopping Cart</h4>
            <p class="lead">Review your selected products before checkout</p>

        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($cartItems)): ?>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <img src="uploads/<?= htmlspecialchars($item['productPic']) ?>" alt="Product Image" class="img-fluid" style="width: 100px;">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </td>
                                    <td>₱<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-secondary" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] - 1 ?>)" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>-</button>
                                        <?= htmlspecialchars($item['quantity']) ?>
                                        <button class="btn btn-sm btn-secondary" onclick="updateQuantity(<?= $item['product_id'] ?>, <?= $item['quantity'] + 1 ?>)" <?= $item['quantity'] >= $item['stock'] ? 'disabled' : '' ?>>+</button>
                                    </td>
                                    <td>₱<?= number_format($item['total_price'], 2) ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(<?= $item['cart_item_id'] ?>)">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Your cart is empty.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end text-dark mt-4">
                <h4>Total Amount: <span>₱<?= number_format($totalAmount, 2) ?></span></h4>
                <a href="checkout.php" class="btn btn-success mt-3">Checkout</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function removeFromCart(cartItemId) {
            fetch('remove_from_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        cart_item_id: cartItemId
                    })
                }).then(response => response.json())
                .then(data => location.reload())
                .catch(err => alert('Error removing item.'));
        }

        function updateQuantity(productId, newQuantity) {
            fetch('update_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        product_id: productId,
                        quantity: newQuantity
                    })
                }).then(response => response.json())
                .then(data => location.reload())
                .catch(err => alert('Error updating quantity.'));
        }
    </script>
</body>

</html>