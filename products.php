<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Products - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="wrapper">
    <nav id="sidebar" class="text-white">
      <div class="sidebar-header">
        <h3>Admin Panel</h3>
      </div>

      <ul class="list-unstyled">
        <li>
          <a href="dashboard.php">Dashboard</a>
        </li>
        <li class="active">
          <a href="products.php">Products</a>
        </li>
        <li>
          <a href="orders.php">Orders</a>
        </li>
        <li>
          <a href="accounts.php">Accounts</a>
        </li>
      </ul>

      <div class="mt-auto">
        <button class="btn btn-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
          Logout
        </button>
      </div>
    </nav>

    <div id="content">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="mt-4">Products</h1>
            <p class="text-muted">Manage your product inventory</p>
          </div>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Add Product
          </button>
        </div>

        <div class="row">
          <?php
          $stmt = $pdo->query("SELECT * FROM products");
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $productId = htmlspecialchars($row['product_id']);
          ?>
            <div class="col-md-4 mb-4">
              <div class="card">
                <div class="product-image-card">
                <img id="product-img" src="uploads/<?php echo htmlspecialchars($row['productPic']); ?>" class="card-img-top img-fluid" alt="Product Image">
                </div>
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                  <p class="card-text"><strong>Price:</strong> ₱<?php echo number_format($row['price'], 2); ?></p>
                  <p class="card-text"><strong>Stock:</strong> <?php echo htmlspecialchars($row['stock']); ?></p>
                  <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                  <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                  <button class="btn bg-success text-white btn-sm w-100" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $productId; ?>">
                    Edit
                  </button>
                  <button class="btn bg-danger text-white btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?php echo $productId; ?>">
                    Delete
                  </button>
                </div>
              </div>
            </div>

            <div class="modal fade" id="editProductModal<?php echo $productId; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $productId; ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?php echo $productId; ?>">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?php echo $productId; ?>">
                      <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($row['price']); ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($row['stock']); ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                          <option value="Perfume" <?php echo $row['category'] === 'Perfume' ? 'selected' : ''; ?>>Perfume</option>
                          <option value="Pomade" <?php echo $row['category'] === 'Pomade' ? 'selected' : ''; ?>>Pomade</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Update Product Picture</label>
                        <input type="file" name="productPic" class="form-control">
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="editProduct" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="deleteProductModal<?php echo $productId; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $productId; ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $productId; ?>">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                    <form action="" method="post">
                      <input type="hidden" name="id" value="<?php echo $productId; ?>">
                      <div class="modal-footer">
                        <button type="submit" name="deleteProduct" class="btn btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </div>

        <div class="modal fade" id="addProductModal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <form method="POST" action="" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" step="0.01" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Product Picture</label>
                    <input type="file" name="productPic" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                      <option value="Perfume">Perfume</option>
                      <option value="Pomade">Pomade</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="addProduct" class="btn btn-primary">Add Product</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to log out?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
          <a href="index.html" class="btn btn-danger">
            Yes, Logout
          </a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>



<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  ob_start();
    if (isset($_POST['addProduct'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $productPic = $_FILES['productPic']['name'];

        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($productPic);

        if (move_uploaded_file($_FILES['productPic']['tmp_name'], $uploadFile)) {
            $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, category, description, productPic) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $price, $stock, $category, $description, $productPic]);
            echo "<script>window.location.href='products.php';</script>";
        exit;
        } else {
            echo "Error uploading file.";
        }
    }

    if (isset($_POST['editProduct'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $productPic = $_FILES['productPic']['name'];

        if (!empty($productPic)) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($productPic);
            if (move_uploaded_file($_FILES['productPic']['tmp_name'], $uploadFile)) {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ?, category = ?, description = ?, productPic = ? WHERE product_id = ?");
                $stmt->execute([$name, $price, $stock, $category, $description, $productPic, $id]);
            } else {
                echo "Error uploading file.";
            }
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ?, category = ?, description = ? WHERE product_id = ?");
            $stmt->execute([$name, $price, $stock, $category, $description, $id]);
        }
        echo "<script>window.location.href='products.php';</script>";
exit;
    }

    if (isset($_POST['deleteProduct'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("SELECT productPic FROM products WHERE product_id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            $productPicPath = 'uploads/' . $product['productPic'];
            if (file_exists($productPicPath)) {
                unlink($productPicPath);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$id]);
        echo "<script>window.location.href='products.php';</script>";
        exit;
    }
    ob_end_flush();
}
?>
