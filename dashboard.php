<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="wrapper">
    <nav id="sidebar" class="text-white">
      <div class="sidebar-header">
        <h3>Admin Panel</h3>
      </div>

      <ul class="list-unstyled">
        <li class="active">
          <a href="dashboard.php">Dashboard</a>
        </li>
        <li>
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
      <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="mt-4">Dashboard</h1>
          </div>
          <div class="ms-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
              Generate Report
            </button>
          </div>
        </div>

        <div class="row">
          <?php
          $stmt = $pdo->query("SELECT SUM(amount) AS total_revenue FROM revenue");
          $revenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

          $stmt = $pdo->query("SELECT COUNT(*) AS total_orders FROM orders");
          $total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'] ?? 0;

          $stmt = $pdo->query("SELECT COUNT(*) AS total_pending FROM orders WHERE status = 'Pending'");
          $total_pending = $stmt->fetch(PDO::FETCH_ASSOC)['total_pending'] ?? 0;

          $stmt = $pdo->query("SELECT COUNT(*) AS total_shipped FROM orders WHERE status = 'Shipped'");
          $total_shipped = $stmt->fetch(PDO::FETCH_ASSOC)['total_shipped'] ?? 0;

          $stmt = $pdo->query("SELECT COUNT(*) AS total_completed FROM orders WHERE status = 'Completed'");
          $total_completed = $stmt->fetch(PDO::FETCH_ASSOC)['total_completed'] ?? 0;
          ?>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="card-text">₱<?php echo number_format($revenue, 2); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p class="card-text"><?php echo $total_orders; ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Shipped Orders</h5>
                <p class="card-text"><?php echo $total_shipped; ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Completed Orders</h5>
                <p class="card-text"><?php echo $total_completed; ?></p>
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

  <div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="generateReportModalLabel">Generate Report</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to generate the report for revenue and orders?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="generate_report.php" class="btn btn-primary">Yes, Generate Report</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</body>

</html>