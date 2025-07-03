<?php
session_start();
include('config.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch user login
    $stmt = $conn->prepare("SELECT UserID, PasswordHash, CustID FROM USER_LOGIN WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $loginResult = $stmt->get_result();

    if ($loginResult->num_rows === 1) {
        $loginData = $loginResult->fetch_assoc();

        if (password_verify($password, $loginData['PasswordHash'])) {
            $custID = $loginData['CustID'];

            // Fetch customer details
            $stmt = $conn->prepare("SELECT * FROM CUSTOMER WHERE CustID = ?");
            $stmt->bind_param("i", $custID);
            $stmt->execute();
            $custResult = $stmt->get_result();

            if ($custResult->num_rows === 1) {
                $customer = $custResult->fetch_assoc();

                // Parse shipping address
                list($street, $city, $province, $zip, $country) = array_map('trim', explode(',', $customer['ShippingAddress']));

                // Store session data
                $_SESSION['user_id'] = $loginData['UserID'];
                $_SESSION['cust_id'] = $custID;
                $_SESSION['user_data'] = [
                    'fullname' => $customer['FullName'],
                    'email' => $customer['Email'],
                    'phone' => $customer['PhoneNumber'],
                    'street' => $street,
                    'city' => $city,
                    'province' => $province,
                    'zip' => $zip,
                    'country' => $country,
                    'username' => $username
                ];

                header("Location: account.php");
                exit();
            } else {
                $message = "Customer data not found.";
            }
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "Invalid username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login | CoffeeHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fef9f5;
        }
        nav {
            background-color: #4E342E;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 2rem;
            color: #fff !important;
        }
        .navbar-brand img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 50%;
        }
        .nav-link {
            color: #fff !important;
            font-size: 1.05rem;
        }
        .nav-link:hover {
            color: #D6A77A !important;
        }
        .login-container {
            background: #fff;
            border-radius: 12px;
            padding: 40px;
            max-width: 450px;
            margin: 80px auto;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 500;
        }
        h2 {
            color: #4B2E2A;
        }
        .login-btn {
            background-color: #D6A77A;
            color: #fff;
            font-weight: 600;
        }
        .login-btn:hover {
            background-color: #c28e67;
        }
        footer {
            background-color: #4E342E;
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }
        footer a {
            color: #D6A77A;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <img src="images/logo.png" alt="CoffeeHub Logo">
            <span>Coffee Hub</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a class="nav-link" href="whatweoffer.php">What We Offer</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="account.php">Account</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fas fa-shopping-cart me-1"></i> Cart
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Login Form -->
<div class="login-container">
    <form action="login.php" method="POST">
        <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Login</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn login-btn">Login</button>
        </div>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 CoffeeHub | All Rights Reserved</p>
    <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
