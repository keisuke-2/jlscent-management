<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finalproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = $success = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email_or_phone'])) {
    $email_or_phone = trim($_POST['email_or_phone']);

    if (empty($email_or_phone)) {
        $error = "Please enter your email or phone number.";
    } else {
        $query = "SELECT * FROM users WHERE email = ? OR phone_no = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $email_or_phone, $email_or_phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_id = $user['user_id'];
            $email = $user['email'];

            $token = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $insert_query = "INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("iss", $user_id, $token, $expiry);
            $insert_stmt->execute();

            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?token=$token";
            $subject = "Password Reset Request";
            $message = "Click the link to reset your password: $reset_link\nThis link will expire in 1 hour.";
            $headers = "From: noreply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                $success = "A password reset link has been sent to your email.";
            } else {
                $error = "Failed to send reset email. Please try again.";
            }
        } else {
            $error = "No account found with the provided email or phone number.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'], $_POST['confirm_password'], $_POST['token'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "Invalid or expired token.";
        } else {
            $user_reset = $result->fetch_assoc();
            $user_id = $user_reset['user_id'];

            $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $update_query = "UPDATE users SET password_hash = ? WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("si", $password_hash, $user_id);
            $update_stmt->execute();

            $delete_query = "DELETE FROM password_resets WHERE token = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("s", $token);
            $delete_stmt->execute();

            $success = "Password reset successful. You can now <a href='login.php'>log in</a>.";
        }
    }
}

$reset_mode = false;
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $reset_mode = true;
    } else {
        $error = "Invalid or expired token.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>JL Scent | Forgot Password</title>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>JL Scent</h1>
            <p><?php echo $reset_mode ? "Reset your password below." : "Forgot your password? We'll help you reset it."; ?></p>
        </div>

        <div class="right-section">
            <div class="login-card">
                <?php if ($reset_mode): ?>
                    <form method="POST" action="">
                        <h2>Reset Your Password</h2>
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <input type="password" name="new_password" placeholder="New Password" required>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                        <button type="submit" class="reset-button">Reset Password</button>
                    </form>
                <?php else: ?>
                    <form method="POST" action="">
                        <h2>Reset Password</h2>
                        <p class="form-description">Enter your email or phone number to receive a password reset link.</p>
                        <input type="text" name="email_or_phone" placeholder="Email or Phone Number" required>
                        <button type="submit" class="reset-button">Send Reset Link</button>
                    </form>
                <?php endif; ?>

                <?php if ($error): ?>
                    <p class="error-message"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <?php if ($success): ?>
                    <p class="success-message"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>

                <?php if (!$reset_mode): ?>
                    <a href="login.php" class="back-to-login">Back to Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
