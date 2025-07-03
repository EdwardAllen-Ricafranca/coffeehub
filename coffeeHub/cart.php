<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['cust_id'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'CoffeeShopDB';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$customerID = $_SESSION['cust_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['productID'])) {
        $productID = $_POST['productID'];
        $action = $_POST['action'];

        // Get current quantity
        $stmt = $pdo->prepare("SELECT Quantity FROM CART WHERE ProductID = :productID AND CustID = :customerID");
        $stmt->execute(['productID' => $productID, 'customerID' => $customerID]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($current) {
            $qty = $current['Quantity'];

            if ($action === 'increase') {
                $qty++;
            } elseif ($action === 'decrease' && $qty > 1) {
                $qty--;
            }

            $updateSql = "UPDATE CART SET Quantity = :quantity WHERE ProductID = :productID AND CustID = :customerID";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute(['quantity' => $qty, 'productID' => $productID, 'customerID' => $customerID]);
        }

        header("Location: cart.php");
        exit();
    }

    if (isset($_POST['remove']) && isset($_POST['productID'])) {
        $productID = $_POST['productID'];
        $removeSql = "DELETE FROM CART WHERE ProductID = :productID AND CustID = :customerID";
        $removeStmt = $pdo->prepare($removeSql);
        $removeStmt->execute(['productID' => $productID, 'customerID' => $customerID]);
        header("Location: cart.php");
        exit();
    }
}

$sql = "SELECT p.ProductID, p.ProductDescription, p.ProductPrice, p.ProductImage, c.Quantity
        FROM CART c
        JOIN PRODUCTS p ON c.ProductID = p.ProductID
        WHERE c.CustID = :customerID";
$stmt = $pdo->prepare($sql);
$stmt->execute(['customerID' => $customerID]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Cart | Brew Haven</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fef9f5;
      display: flex;
      flex-direction: column;
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
      transition: 0.3s;
    }
    .nav-link:hover {
      color: #D6A77A !important;
    }

    .cart-container {
      margin-top: 60px;
      flex: 1 0 auto;
    }

    .cart-item {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }

    .cart-item img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      border-radius: 8px;
    }

    .item-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #4B2E2A;
    }

    .item-price {
      color: #D6A77A;
      font-size: 1.1rem;
      font-weight: 500;
    }

    .btn-remove {
      color: #4E342E;
      font-size: 0.9rem;
      transition: 0.3s;
      text-decoration: none;
      padding: 5px 10px;
      border: 1px solid #4E342E;
      border-radius: 4px;
      background: transparent;
    }
    .btn-remove:hover {
      background-color: #4E342E;
      color: #fff;
    }

    .total-section {
      text-align: right;
      font-size: 1.2rem;
      font-weight: bold;
      color: #4B2E2A;
    }

    .btn-checkout {
      background-color: #4E342E;
      color: white;
      border: none;
      padding: 10px 30px;
      font-weight: 600;
      border-radius: 5px;
      transition: 0.3s;
    }
    .btn-checkout:hover {
      background-color: #6D4C41;
    }

    .quantity-controls {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .quantity-controls input[type="text"] {
      width: 50px;
      text-align: center;
      border: none;
      background-color: transparent;
      font-weight: bold;
    }

    footer {
      background-color: #4E342E;
      color: #fff;
      padding: 30px 0;
      text-align: center;
      flex-shrink: 1;
    }
    footer a {
      color: #D6A77A;
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
          <a class="nav-link active" href="cart.php">
            <i class="fas fa-shopping-cart me-1"></i> Cart
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Cart Content -->
<div class="container cart-container">
  <h2 class="fw-bold mb-4 text-center">🛒 Your Shopping Cart</h2>

  <?php if (empty($cartItems)): ?>
    <p class="text-center">Your cart is empty. Add items to your cart!</p>
  <?php else: ?>
    <?php foreach ($cartItems as $item): ?>
      <div class="cart-item row align-items-center" data-price="<?= $item['ProductPrice'] ?>">
        <div class="col-md-2 text-center">
          <img src="images/<?= $item['ProductImage'] ?>" alt="<?= $item['ProductDescription'] ?>">
        </div>
        <div class="col-md-4">
          <p class="item-title mb-1"><?= $item['ProductDescription'] ?></p>
          <p class="item-price mb-0">₱<span class="unit-price"><?= $item['ProductPrice'] ?></span></p>
        </div>
        <div class="col-md-3">
          <form action="cart.php" method="POST" class="quantity-controls">
            <input type="hidden" name="productID" value="<?= $item['ProductID'] ?>">
            <button type="submit" name="action" value="decrease" class="btn btn-outline-secondary">−</button>
            <input type="text" name="quantity_display" value="<?= $item['Quantity'] ?>" readonly />
            <button type="submit" name="action" value="increase" class="btn btn-outline-secondary">+</button>
          </form>
        </div>
        <div class="col-md-2 text-end">
          <p class="item-price mb-0">₱<span class="item-total"><?= $item['ProductPrice'] * $item['Quantity'] ?></span></p>
        </div>
        <div class="col-md-1 text-end">
          <form action="cart.php" method="POST">
            <input type="hidden" name="productID" value="<?= $item['ProductID'] ?>">
            <button class="btn btn-link btn-remove" name="remove">Remove</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <div class="mt-4 mb-4 d-flex justify-content-between align-items-center">
    <div></div>
    <div>
      <p class="total-section">Total: ₱<span id="cart-total">0</span></p>
      <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <p>&copy; 2025 Coffee Hub | Clint Aldrich Reyes | Edward Allen Ricafranca</p>
  <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
</footer>

<script>
  function updateTotal() {
    let total = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
      const unitPrice = parseFloat(item.dataset.price);
      const quantity = parseInt(item.querySelector('input[name="quantity_display"]').value);
      const itemTotal = unitPrice * quantity;
      item.querySelector('.item-total').textContent = itemTotal.toFixed(2);
      total += itemTotal;
    });
    document.getElementById('cart-total').textContent = total.toFixed(2);
  }

  updateTotal();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
