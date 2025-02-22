<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders - Admin Dashboard</title>
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
        <li>
          <a href="products.php">Products</a>
        </li>
        <li class="active">
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
          <h1 class="mt-4">Orders</h1>
          <p class="text-muted">View and manage customer orders</p>

          <div class="card">
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Email</th>
                    <th>Address</th> 
                    <th>Total Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmt = $pdo->query("
                      SELECT orders.order_id, orders.user_id, orders.total_amount, orders.status, users.email, users.home_address
                      FROM orders
                      JOIN users ON orders.user_id = users.user_id
                  ");
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo "<tr>
                          <td>{$row['order_id']}</td>
                          <td>{$row['user_id']}</td>
                          <td>{$row['email']}</td>
                          <td>{$row['home_address']}</td>
                          <td>₱" . number_format($row['total_amount'], 2) . "</td>
                          <td>
                              <form method='POST' action='update_status.php' class='d-inline'>
                                  <select name='status' class='form-select' onchange='this.form.submit()'>
                                      <option value='pending'" . ($row['status'] == 'pending' ? ' selected' : '') . ">Pending</option>
                                      <option value='shipped'" . ($row['status'] == 'shipped' ? ' selected' : '') . ">Shipped</option>
                                      <option value='completed'" . ($row['status'] == 'completed' ? ' selected' : '') . ">Completed</option>
                                      <option value='canceled'" . ($row['status'] == 'canceled' ? ' selected' : '') . ">Canceled</option>
                                  </select>
                                  <input type='hidden' name='order_id' value='{$row['order_id']}'>
                              </form>
                          </td>
                      </tr>";
                  }
                  ?>
                </tbody>
              </table>
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
