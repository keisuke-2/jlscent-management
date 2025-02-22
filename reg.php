<?php 
include('db.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);
    $home_address = trim($_POST['home_address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Email is already registered.";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (username, email, phone_no, home_address, password_hash, role) 
                    VALUES (:username, :email, :phone_no, :home_address, :password_hash, 'customer')";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone_no', $phone_no, PDO::PARAM_STR);
            $stmt->bindParam(':home_address', $home_address);
            $stmt->bindParam(':password_hash', $password_hash);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Error: Could not complete registration.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JL Scent | Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reg.css">
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 bg-gradient">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card shadow-sm rounded-4 p-4">
                <h2 class="text-center text-dark mb-4">Create Your Account</h2>
                <form id="registerForm" method="POST" action="">
                    <div class="mb-3">
                        <input type="text" id="reg-username" name="username" class="form-control form-control-lg" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" id="reg-email" name="email" class="form-control form-control-lg" placeholder="Email Address" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" id="reg-address" name="home_address" class="form-control form-control-lg" placeholder="Home Address" required>
                    </div>
                    <div class="mb-3">
                        <input type="tel" id="reg-phone" name="phone_no" class="form-control form-control-lg" placeholder="Phone Number" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" id="reg-password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" id="reg-confirm-password" name="confirm_password" class="form-control form-control-lg" placeholder="Confirm Password" required>
                    </div>

                    <?php if (!empty($error)): ?>
                        <p class="text-danger text-center"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary w-100 py-3">Sign Up</button>
                </form>
                <p class="mt-3 text-center">
                    Already have an account? <a href="login.php" class="text-decoration-none text-success">Log In</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
