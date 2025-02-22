<?php
session_start();
include('db.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userInput = isset($_POST['username']) ? trim($_POST['username']) : null;
    $passwordInput = isset($_POST['password']) ? trim($_POST['password']) : null;
    $roleInput = isset($_POST['role']) ? trim($_POST['role']) : null;

    if ($userInput && $passwordInput && $roleInput) {
        try {
            $sql = "SELECT * FROM users WHERE (email = :userInput OR username = :userInput) AND role = :roleInput";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':userInput' => $userInput,
                ':roleInput' => $roleInput,
            ]);

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($passwordInput, $user['password_hash'])) {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        header("Location: home.php");
                        exit();
                    }
                } else {
                    $error = "Invalid username or password!";
                }
            } else {
                $error = "Invalid username or password!";
            }
        } catch (PDOException $e) {
            $error = "An error occurred: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>JL Scent | Login</title>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>JL Scent</h1>
            <p>Discover fragrances that tell your story and leave a lasting impression.</p>
        </div>

        <div class="right-section">
            <div class="login-card">
                <form id="loginForm" method="POST" action="">
                    <h2>Welcome Back</h2>
                    <p class="form-description">Log in to access your personalized fragrance journey.</p>
                    <input type="text" name="username" id="username" placeholder="Email or Phone Number" required>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <select name="role" id="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                    </select>
                    <button type="submit" class="login-button">Log In</button>

                    <?php if ($error): ?>
                        <p class="error-message"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
                    <hr>
                    <button type="button" class="create-account" onclick="location.href='reg.php'">Create New Account</button>
                </form>
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
