<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$profile_update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $home_address = trim($_POST['home_address']);
    $phone_no = trim($_POST['phone_no']);

    try {
        $sql = "UPDATE users SET home_address = :home_address, phone_no = :phone_no WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':home_address' => $home_address,
            ':phone_no' => $phone_no,
            ':user_id' => $user_id
        ]);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $profile_update_message = 'Profile updated successfully!';
    } catch (PDOException $e) {
        $profile_update_message = 'Error updating profile: ' . $e->getMessage();
    }
}

$password_update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $password_update_message = 'New passwords do not match!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($current_password, $user['password_hash'])) {
                $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id");
                $stmt->execute([
                    ':password_hash' => $new_password_hash,
                    ':user_id' => $user_id
                ]);
                $password_update_message = 'Password updated successfully!';
            } else {
                $password_update_message = 'Current password is incorrect!';
            }
        } catch (PDOException $e) {
            $password_update_message = 'Error updating password: ' . $e->getMessage();
        }
    }
}

try {
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    echo "An error occurred: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JL Scent | My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/prof.css">
</head>

<body>
    <div class="container profile-container">
        <h4 class="font-weight-bold py-3 text-center">My Profile</h4>

        <div class="card profile-card shadow-lg">
            <div class="row no-gutters">
                <div class="col-md-3 profile-sidebar bg-light text-dark">
                    <div class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">
                            <i class="fas fa-user-circle"></i> General
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                    </div>
                </div>

                <div class="col-md-9 text-dark">
                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="account-general">


                            <form method="POST">
                                <div class="form-group text-dark">
                                    <label>Username</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                </div>
                                <div class="form-group text-dark">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="home_address" value="<?= htmlspecialchars($user['home_address']) ?>">
                                </div>
                                <div class="form-group text-dark">
                                    <label>E-mail</label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                </div>
                                <div class="form-group text-dark">
                                    <label>Phone</label>
                                    <input type="tel" class="form-control" name="phone_no" value="<?= htmlspecialchars($user['phone_no']) ?>">
                                </div>
                                <div class="text-right text-dark">
                                    <button type="submit" name="save_changes" class="btn btn-primary">Save Changes</button>
                                    <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='home.php'">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="account-change-password">
                            <form method="POST">
                                <div class="form-group text-dark">
                                    <label>Current Password</label>
                                    <input type="password" class="form-control" name="current_password" placeholder="Enter current password" required>
                                </div>
                                <div class="form-group text-dark">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="new_password" placeholder="Enter new password" required>
                                </div>
                                <div class="form-group text-dark">
                                    <label>Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password" required>
                                </div>
                                <div class="text-right text-dark">
                                    <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
                                    <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='home.php'">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="successModalLabel">Alert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-dark">
                    <?= $profile_update_message ?: $password_update_message ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-dark">
                    <?= $profile_update_message ?: $password_update_message ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($profile_update_message || $password_update_message) : ?>
        <script>
            $(document).ready(function() {
                $('#successModal').modal('show');
            });
        </script>
    <?php endif; ?>

</body>
</html>
