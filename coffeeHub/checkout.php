<?php
session_start();
include('config.php');

if (!isset($_SESSION['cust_id'])) {
    header("Location: login.php");
    exit();
}

$cust_id = $_SESSION['cust_id'];

// Fetch customer info
$stmt = $conn->prepare("SELECT FullName, Email, PhoneNumber, ShippingAddress FROM CUSTOMER WHERE CustID = ?");
$stmt->bind_param("i", $cust_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Fetch cart items with product details
$cart_stmt = $conn->prepare("
    SELECT PRODUCTS.ProductDescription, PRODUCTS.ProductPrice, PRODUCTS.ProductImage, CART.Quantity
    FROM CART
    JOIN PRODUCTS ON CART.ProductID = PRODUCTS.ProductID
    WHERE CART.CustID = ?
");
$cart_stmt->bind_param("i", $cust_id);
$cart_stmt->execute();
$cart_items = $cart_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout | Brew Haven</title>
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
    .form-section {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      margin-bottom: 40px;
    }
    .checkout-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #4B2E2A;
      margin-bottom: 20px;
    }
    .btn-placeorder {
      background-color: #D6A77A;
      color: #fff;
      padding: 12px 30px;
      font-size: 1.1rem;
      border: none;
    }
    .btn-placeorder:hover {
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
      margin: 0 8px;
    }
    footer a:hover {
      text-decoration: underline;
    }
    .order-summary img {
      width: 60px;
      height: 60px;
      object-fit: contain;
    }
    .info-box {
      padding: 10px 15px;
      background-color: #f8f1ea;
      border-radius: 6px;
      margin-bottom: 12px;
      font-weight: 500;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <img src="images/logo.png" alt="Brew Haven Logo">
      <span>Coffee Hub</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto gap-3">
        <li class="nav-item"><a class="nav-link" href="whatweoffer.php">What We Offer</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="account.php">Account</a></li>
        <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php">
            <i class="fas fa-shopping-cart me-1"></i> Cart
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Checkout Info -->
<div class="container my-5">
  <div class="row g-5">
    <!-- Customer Info -->
    <div class="col-md-7">
      <div class="form-section">
        <h2 class="checkout-title">Customer Information</h2>
        <div class="info-box">👤 Name: <?= htmlspecialchars($customer['FullName']) ?></div>
        <div class="info-box">📧 Email: <?= htmlspecialchars($customer['Email']) ?></div>
        <div class="info-box">📱 Phone: <?= htmlspecialchars($customer['PhoneNumber']) ?></div>
        <div class="info-box">📍 Address: <?= htmlspecialchars($customer['ShippingAddress']) ?></div>

        <form id="checkoutForm">
          <div class="mb-3 mt-4">
            <label class="form-label">Payment Method</label>
            <select class="form-select" required>
              <option selected disabled>Select payment method</option>
              <option value="credit">Credit Card</option>
              <option value="paypal">PayPal</option>
              <option value="cod">Cash on Delivery</option>
            </select>
          </div>
          <button type="submit" class="btn btn-placeorder">Place Order</button>
        </form>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="col-md-5">
      <div class="form-section order-summary">
        <h2 class="checkout-title">Order Summary</h2>
        <?php
        $total = 0;
        if ($cart_items):
          foreach ($cart_items as $item):
            $itemTotal = $item['ProductPrice'] * $item['Quantity'];
            $total += $itemTotal;
        ?>
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-3">
              <div>
                <div class="fw-semibold"><?= htmlspecialchars($item['ProductDescription']) ?></div>
                <small>Qty: <?= $item['Quantity'] ?></small>
              </div>
            </div>
            <div>₱<?= number_format($itemTotal, 2) ?></div>
          </div>
        <?php
          endforeach;
        else:
        ?>
          <p>Your cart is empty.</p>
        <?php endif; ?>
        <hr />
        <p class="text-end fw-bold">Total: ₱<?= number_format($total, 2) ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <p>&copy; 2025 Brew Haven | Clint Aldrich Reyes</p>
  <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
</footer>

<script>
  document.getElementById("checkoutForm").addEventListener("submit", function (e) {
    e.preventDefault();
    alert("✅ Thank you! Your order has been placed.");
    window.location.href = "index.php";
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
