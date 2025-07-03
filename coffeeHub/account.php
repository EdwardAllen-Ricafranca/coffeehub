<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_data'])) {
    $_SESSION['message'] = "Must be logged in to view account page";
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome to CoffeeHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F5F5F5;
        }

        /* Navbar */
        nav {
            background-color: #2C1810;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 28px;
            color: #fff !important;
        }
        .nav-link {
            color: #fff !important;
            font-size: 1.05rem;
            transition: 0.3s;
        }
        .nav-link:hover {
            color: #C8A27A !important;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #2C1810 0%, #4A3428 100%);
            color: #fff;
            padding: 80px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .hero-section p {
            font-size: 1.2rem;
        }

        /* User Info Section */
        .info-section {
            background-color: #F5F5F5;
            padding: 60px 0;
        }

        .info-box {
            background: #fff;
            border: 3px solid #2C1810;
            border-radius: 15px;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .info-box h2 {
            color: #2C1810;
            text-align: center;
            margin-bottom: 30px;
        }

        .info-box .info {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #4A3428;
        }

        .logout-section {
            padding: 50px 0;
            text-align: center;
        }

        .btn-logout {
            background-color: #C8A27A;
            color: #fff;
            font-size: 1.1rem;
            padding: 12px 30px;
            border: none;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-logout:hover {
            background-color: #B08E6A;
            color: #fff;
        }

        /* Footer */
        footer {
            background-color: #2C1810;
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }
        footer a {
            color: #C8A27A;
            text-decoration: none;
            margin: 0 8px;
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
            <img src="images/logo.png" alt="CoffeeHub Logo" width="60" height="60" style="object-fit: contain; border-radius: 50%;">
            <span>CoffeeHub</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="whatweoffer.php">What We Offer</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="account.php">Account</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h1>
        <p>Thank you for being part of CoffeeHub. Here's your account information:</p>
    </div>
</div>

<!-- Display User Info -->
<div class="info-section">
    <div class="container">
        <div class="info-box">
            <h2><i class="fas fa-user-check"></i> Account Details</h2>

            <div class="info"><span class="info-label">Full Name:</span> <?php echo htmlspecialchars($user['fullname'] ?? ''); ?></div>
            <div class="info"><span class="info-label">Phone Number:</span> <?php echo htmlspecialchars($user['phone'] ?? ''); ?></div>
            <div class="info"><span class="info-label">Email:</span> <?php echo htmlspecialchars($user['email'] ?? ''); ?></div>

            <!-- Shipping Address -->
            <div class="info"><span class="info-label">Street:</span> <?php echo htmlspecialchars($user['street'] ?? ''); ?></div>
            <div class="info"><span class="info-label">City:</span> <?php echo htmlspecialchars($user['city'] ?? ''); ?></div>
            <div class="info"><span class="info-label">Province/State:</span> <?php echo htmlspecialchars($user['province'] ?? ''); ?></div>
            <div class="info"><span class="info-label">Zip Code:</span> <?php echo htmlspecialchars($user['zip'] ?? ''); ?></div>
            <div class="info"><span class="info-label">Country:</span> <?php echo htmlspecialchars($user['country'] ?? ''); ?></div>

            <hr>

            <div class="info"><span class="info-label">Username:</span> <?php echo htmlspecialchars($user['username']); ?></div>
            <div class="info">
              <span class="info-label">Password:</span> ********
            </div>
        </div>
    </div>
</div>

<!-- Logout Button -->
<div class="logout-section">
    <a href="logout.php" class="btn-logout">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 CoffeeHub | All Rights Reserved</p>
    <div>
        <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
