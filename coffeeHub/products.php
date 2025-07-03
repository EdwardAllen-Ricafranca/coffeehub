<?php
include('config.php');
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Product not specified or invalid.");
}



$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM PRODUCTS WHERE ProductID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Product not found.");
}
$product = $result->fetch_assoc();

// Add long description only if not already present from database
$hardcodedDescriptions = [
    'Espresso Shot' => 'Strong, bold, and aromatic espresso shot made with freshly ground Arabica beans.',
    'Caffe Americano' => 'Rich espresso diluted with hot water, a classic favorite for coffee lovers.',
    'Latte' => 'A creamy blend of espresso and steamed milk, topped with a light froth.',
    'Cappuccino' => 'A well-balanced combination of espresso, steamed milk, and foam.',
    'Mocha' => 'A sweet espresso-based drink with chocolate syrup and steamed milk.',
    'Caramel Macchiato' => 'A smooth espresso drink with vanilla syrup, steamed milk, and caramel drizzle.',
    'Vanilla Cold Brew' => 'Cold brew coffee with a touch of vanilla syrup, served over ice.',
    'Iced Americano' => 'Espresso diluted with cold water and served over ice.',
    'Iced Latte' => 'A chilled espresso-based drink with steamed milk and ice.',
    'Iced Mocha' => 'A cold version of mocha, with espresso, chocolate syrup, and milk over ice.',
    'Hazelnut Frappe' => 'A frosty blend of hazelnut syrup, coffee, and ice.',
    'Classic Brewed Coffee' => 'Freshly brewed coffee made with high-quality Arabica beans.',
    'Cinnamon Roll' => 'Flaky cinnamon roll with a sweet glaze.',
    'Blueberry Muffin' => 'A soft, moist muffin loaded with blueberries.',
    'Chocolate Chip Cookie' => 'Classic chocolate chip cookies with a soft and chewy texture.',
    'Banana Bread Slice' => 'Rich and moist banana bread with a perfect hint of sweetness.',
    'Cheese Croissant' => 'Flaky croissant filled with a rich cheese filling.',
    'Tiramisu Slice' => 'Classic tiramisu made with layers of espresso-soaked ladyfingers and mascarpone cream.',
    'Matcha Latte' => 'A vibrant, creamy matcha green tea latte.',
    'Affogato' => 'A delicious dessert made with a scoop of vanilla ice cream and espresso poured over.',
];

// Use hardcoded description only if none exists from database
if (empty($product['LongDescription']) && isset($hardcodedDescriptions[$product['ProductDescription']])) {
    $product['LongDescription'] = $hardcodedDescriptions[$product['ProductDescription']];
}


// Map image filenames
$imageMap = [
    'Espresso Shot' => 'shot.png',
    'Caffe Americano' => 'caffeamericano.png',
    'Latte' => 'latte.png',
    'Cappuccino' => 'cappuccino.png',
    'Mocha' => 'mocha.png',
    'Caramel Macchiato' => 'macchiato.png',
    'Vanilla Cold Brew' => 'vanilla.png',
    'Iced Americano' => 'americano.png',
    'Iced Latte' => 'icelatte.png',
    'Iced Mocha' => 'icemocha.png',
    'Hazelnut Frappe' => 'hazelnut.png',
    'Classic Brewed Coffee' => 'coldbrew.png',
    'Cinnamon Roll' => 'cinnamon.png',
    'Blueberry Muffin' => 'muffin.png',
    'Chocolate Chip Cookie' => 'cookie.png',
    'Banana Bread Slice' => 'banana.png',
    'Cheese Croissant' => 'cheese.png',
    'Tiramisu Slice' => 'tiramisu.png',
    'Matcha Latte' => 'matcha.png',
    'Affogato' => 'affogato.png'
];

$product['ProductImage'] = $imageMap[$product['ProductDescription']] ?? 'default.png';

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cust_id'])) {
        echo "<script>alert('Please login to add to cart.'); window.location.href='login.php';</script>";
        exit();
    }

    $custID = $_SESSION['cust_id'];
    $productID = $product['ProductID'];
    $quantity = intval($_POST['quantity']);

    $checkStmt = $conn->prepare("SELECT * FROM CART WHERE CustID = ? AND ProductID = ?");
    $checkStmt->bind_param("ii", $custID, $productID);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $updateStmt = $conn->prepare("UPDATE CART SET Quantity = Quantity + ? WHERE CustID = ? AND ProductID = ?");
        $updateStmt->bind_param("iii", $quantity, $custID, $productID);
        $updateStmt->execute();
    } else {
        $insertStmt = $conn->prepare("INSERT INTO CART (CustID, ProductID, Quantity) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iii", $custID, $productID, $quantity);
        $insertStmt->execute();
    }

    echo "<script>alert('Product added to cart!'); window.location.href='cart.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($product['ProductDescription']) ?> | Brew Haven</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      transition: 0.3s;
    }
    .nav-link:hover {
      color: #D6A77A !important;
    }
    .product-container {
      padding: 60px 0;
    }
    .product-image {
      width: 100%;
      max-height: 300px;
      object-fit: contain;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .product-name {
      font-size: 2rem;
      font-weight: bold;
      color: #4B2E2A;
      margin-bottom: 20px;
    }
    .product-price {
      color: #D6A77A;
      font-size: 1.7rem;
      font-weight: 600;
    }
    .price-range {
      font-size: 1rem;
      color: #777;
      margin-top: 10px;
    }
    .product-description {
      margin-top: 20px;
      font-size: 1rem;
      color: #4B2E2A;
    }
    .btn-cart {
      background-color: #D6A77A;
      color: #fff;
      font-size: 1.1rem;
      padding: 12px 30px;
      margin-top: 0;
      border: none;
    }
    .btn-cart:hover {
      background-color: #c28e67;
    }
    .input-group > .form-control {
      height: 48px;
    }
    .btn-cart, .btn-outline-secondary {
      height: 48px;
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

<!-- Product Details -->
<div class="container product-container">
  <div class="row align-items-start">
    <div class="col-md-6 text-center">
      <h2 class="product-name"><?= htmlspecialchars($product['ProductDescription']) ?></h2>
      <img src="images/<?= htmlspecialchars($product['ProductImage']) ?>" alt="<?= htmlspecialchars($product['ProductDescription']) ?>" class="product-image">
      <p class="price-range"><?= htmlspecialchars($product['ProductCategory'] ?? 'Available Now') ?></p>
    </div>

    <div class="col-md-6">
      <p class="product-price">₱<?= number_format($product['ProductPrice'], 2) ?></p>
      <div class="product-description">
        <p><?= nl2br(htmlspecialchars($product['LongDescription'] ?? 'No description available.')) ?></p>
        <ul>
          <li>Made with 100% Arabica beans</li>
          <li>Best served chilled with ice</li>
        </ul>
      </div>

      <!-- Add to Cart Form -->
      <form action="products.php?id=<?= $product['ProductID'] ?>" method="POST">
        <div class="input-group mb-3" style="max-width: 380px;">
          <button type="button" class="btn btn-outline-secondary" onclick="changeQty(-1)">−</button>
          <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" required>
          <button type="button" class="btn btn-outline-secondary" onclick="changeQty(1)">+</button>
          <button type="submit" name="add_to_cart" class="btn btn-cart">Add to Cart</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <p>&copy; 2025 Coffee Hub | Clint Aldrich Reyes | Edward Allen Ricafranca</p>
  <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
</footer>

<!-- JS -->
<script>
  function changeQty(change) {
    const qtyInput = document.getElementById('quantity');
    let current = parseInt(qtyInput.value) || 1;
    qtyInput.value = Math.max(1, current + change);
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>