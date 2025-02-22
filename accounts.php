<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
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
        <li>
          <a href="orders.php">Orders</a>
        </li>
        <li class="active">
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
            <h1 class="mt-4">User List</h1>
            <p class="text-muted">Manage Accounts</p>
          </div>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        </div>

        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">User ID</th>
              <th scope="col">Username</th>
              <th scope="col">Email</th>
              <th scope="col">Password Hash</th>
              <th scope="col">Role</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $pdo->query("SELECT user_id, username, email, password_hash, role FROM users");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
              echo "<td>" . htmlspecialchars($row['username']) . "</td>";
              echo "<td>" . htmlspecialchars($row['email']) . "</td>";
              echo "<td>" . htmlspecialchars($row['password_hash']) . "</td>";
              echo "<td>" . htmlspecialchars($row['role']) . "</td>";
              echo "<td>";
              echo "<button class='btn btn-success text-white btn-sm' data-bs-toggle='modal' data-bs-target='#updateUserModal' onclick='fillUpdateModal(" . json_encode($row) . ")'>Update</button> ";
              echo "<button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteUserModal' onclick='setDeleteUserId(" . $row['user_id'] . ")'>Delete</button>";
              echo "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="accounts.php" method="POST" id="addUserForm">
          <input type="hidden" name="action" value="add">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <select class="form-select" id="role" name="role" required>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add User</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="accounts.php" method="POST" id="updateUserForm">
          <input type="hidden" name="action" value="update">
          <input type="hidden" id="update_user_id" name="user_id">
          <div class="modal-header">
            <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="update_username" class="form-label">Username</label>
              <input type="text" class="form-control" id="update_username" name="username" required>
            </div>
            <div class="mb-3">
              <label for="update_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="update_email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="update_password" class="form-label">New Password (Leave blank if no change)</label>
              <input type="password" class="form-control" id="update_password" name="password">
            </div>
            <div class="mb-3">
              <label for="update_role" class="form-label">Role</label>
              <select class="form-select" id="update_role" name="role" required>
                <option value="admin">Admin</option>
                <option value="customer">Customer</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning">Update User</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="accounts.php" method="POST" id="deleteUserForm">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" id="delete_user_id" name="user_id">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this user?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete User</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="index.html" class="btn btn-danger">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function fillUpdateModal(user) {
      document.getElementById('update_user_id').value = user.user_id;
      document.getElementById('update_username').value = user.username;
      document.getElementById('update_email').value = user.email;
      document.getElementById('update_role').value = user.role;
    }

    function setDeleteUserId(user_id) {
      document.getElementById('delete_user_id').value = user_id;
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($_POST['action'] == 'add') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $password, $role])) {
      $message = 'User added successfully';
      $messageType = 'success';
    } else {
      $message = 'Failed to add user';
      $messageType = 'error';
    }
  } elseif ($_POST['action'] == 'update') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password_hash = ?, role = ? WHERE user_id = ?");
      $stmt->execute([$username, $email, $password, $role, $user_id]);
    } else {
      $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?");
      $stmt->execute([$username, $email, $role, $user_id]);
    }

    $message = 'User updated successfully';
    $messageType = 'success';
  } elseif ($_POST['action'] == 'delete') {
    $user_id = $_POST['user_id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    if ($stmt->execute([$user_id])) {
      $message = 'User deleted successfully';
      $messageType = 'success';
    } else {
      $message = 'Failed to delete user';
      $messageType = 'error';
    }
  }
}
?>