<?php
session_start();
include 'db.php';

$userId = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JL Scent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header class="py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="logo text-light fw-bold fs-3">JL Scent</div>
            <nav>
                <a href="cart.php" class="text-light mx-2"><i class="fa-solid fa-cart-shopping"></i> Cart</a>
                <a href="mp.php" class="text-light mx-2"><i class="fa-solid fa-money-bill"></i> My Purchase</a>
                <a href="prof.php" class="text-light mx-2"><i class="fa-solid fa-user"></i> Profile</a>
                <a href="#" class="text-danger mx-2" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </nav>
        </div>
    </header>

    <main class="container my-5">
        <h1 class="text-center text-uppercase fw-bold mb-4">Our Exclusive Collection</h1>

        <section class="my-5">
            <h2 class="text-center fw-bold">Perfumes</h2>
            <div class="row">
                <?php
                $stmt = $pdo->query("SELECT * FROM `products` WHERE `category` = 'Perfume' AND `stock` > 0");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $productId = htmlspecialchars($row['product_id']);
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="uploads/<?php echo htmlspecialchars($row['productPic']); ?>" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text">Price: ₱<?php echo number_format($row['price'], 2); ?></p>
                                <p class="card-text">Stock: <?php echo htmlspecialchars($row['stock']); ?></p>
                                <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                                <button class="btn bg-success text-white btn-sm w-100 add-to-cart-btn" data-product-id="<?php echo $productId; ?>" onclick="addToCart(<?php echo $productId; ?>)">Add to Cart</button>
                                <button class="btn bg-info text-white btn-sm w-100 mt-2 buy-now-btn" onclick="buyNow(<?php echo $productId; ?>)">Buy Now</button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <h2 class="text-center fw-bold">Pomade</h2>
            <div class="row">
                <?php
                $stmt = $pdo->query("SELECT * FROM `products` WHERE `category` = 'Pomade' AND `stock` > 0");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $productId = htmlspecialchars($row['product_id']);
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="uploads/<?php echo htmlspecialchars($row['productPic']); ?>" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text">Price: ₱<?php echo number_format($row['price'], 2); ?></p>
                                <p class="card-text">Stock: <?php echo htmlspecialchars($row['stock']); ?></p>
                                <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                                <button class="btn bg-success text-white btn-sm w-100 add-to-cart-btn" data-product-id="<?php echo $productId; ?>" onclick="addToCart(<?php echo $productId; ?>)">Add to Cart</button>
                                <button class="btn bg-info text-white btn-sm w-100 mt-2 buy-now-btn" onclick="buyNow(<?php echo $productId; ?>)">Buy Now</button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>

    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="addToCartModalLabel">Product Added to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark">
                    The product has been successfully added to your cart.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="cart.php" class="btn btn-primary">Go to Cart</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <a href="index.html" class="btn btn-danger">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function buyNow(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    window.location.href = "checkout.php";
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const addToCartModal = new bootstrap.Modal(document.getElementById('addToCartModal'));
                    addToCartModal.show();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>

</html>
