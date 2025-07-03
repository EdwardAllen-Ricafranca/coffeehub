<?php
session_start();
include('config.php');

$custID = $_SESSION['cust_id'] ?? null;

$categories = ['Cold Drink', 'Hot Drink', 'Baked Good', 'Dessert Drink'];

$order = "ORDER BY date_added DESC";
if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'cheapest':
            $order = "ORDER BY ProductPrice ASC";
            break;
        case 'recommended':
            $order = "ORDER BY ProductRating DESC";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>What We Offer | Brew Haven</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }
    main { flex: 1; }

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

    .category-title {
      font-size: 1.7rem;
      font-weight: bold;
      margin: 40px 0 25px;
      color: #4B2E2A;
    }

    .product-card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      background-color: #fff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease;
      text-align: center;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: contain;
    }
    .product-card .card-body {
      padding: 20px;
    }
    .product-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #4B2E2A;
    }
    .product-price {
      color: #D6A77A;
      font-size: 1.1rem;
      font-weight: 500;
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
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="account.php">Account</a></li>
        <li class="nav-item"><a class="nav-link" href="aboutus.html">About Us</a></li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php">
            <i class="fas fa-shopping-cart me-1"></i> Cart
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container text-center mt-5">
  <h1 class="fw-bold text-dark">What We Offer</h1>
  <p class="text-muted">Explore our curated selection of coffee essentials</p>
</div>

<div class="container px-4">
  <div class="row">
    <div class="col-12 d-flex justify-content-end mb-3">
      <form method="get" action="whatweoffer.php">
        <select class="form-select w-auto" name="sort" onchange="this.form.submit()">
          <option value="newest" <?= isset($_GET['sort']) && $_GET['sort'] == 'newest' ? 'selected' : '' ?>>Newest</option>
          <option value="cheapest" <?= isset($_GET['sort']) && $_GET['sort'] == 'cheapest' ? 'selected' : '' ?>>Cheapest</option>
          <option value="recommended" <?= isset($_GET['sort']) && $_GET['sort'] == 'recommended' ? 'selected' : '' ?>>Most Recommended</option>
        </select>
      </form>
    </div>
  </div>

  <?php
  $imageMap = [
    'Affogato' => 'affogato.png',
    'Tiramisu Slice' => 'tiramisu.png',
    'Cheese Croissant' => 'cheese.png',
    'Banana Bread Slice' => 'banana.png',
    'Chocolate Chip Cookie' => 'cookie.png',
    'Blueberry Muffin' => 'muffin.png',
    'Cinnamon Roll' => 'cinnamon.png',
    'Matcha Latte' => 'matcha.png',
    'Vanilla Cold Brew' => 'vanilla.png',
    'Caramel Macchiato' => 'macchiato.png',
    'Mocha' => 'mocha.png',
    'Cappuccino' => 'cappuccino.png',
    'Latte' => 'latte.png',
    'Caffe Americano' => 'caffeamericano.png',
    'Espresso Shot' => 'shot.png',
    'Hazelnut Frappe' => 'hazelnut.png',
    'Iced Mocha' => 'icemocha.png',
    'Iced Latte' => 'icelatte.png',
    'Iced Americano' => 'americano.png',
  ];

  foreach ($categories as $category) {
    $sql = "SELECT * FROM PRODUCTS WHERE Category = ? $order";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h3 class='category-title px-2'>☕ " . ucfirst($category) . "</h3>";

    if ($result->num_rows > 0) {
      echo "<div class='row g-4'>";
      while ($row = $result->fetch_assoc()) {
        $desc = $row['ProductDescription'];
        $img = $imageMap[$desc] ?? $row['ProductImage'];

        echo "
        <div class='col-md-4 px-3'>
          <a href='products.php?id=" . urlencode($row['ProductID']) . "' class='text-decoration-none'>
            <div class='card product-card pt-3'>
              <img src='images/{$img}' alt='" . htmlspecialchars($desc) . "'>
              <div class='card-body'>
                <h5 class='product-title'>" . htmlspecialchars($desc) . "</h5>
                <p class='product-price'>₱" . number_format($row['ProductPrice'], 2) . "</p>
                <p class='text-warning mb-1'>★ " . number_format($row['ProductRating'], 1) . " / 5</p>
                <p class='text-muted' style='font-size: 0.9rem;'>Added on: " . date('F j, Y', strtotime($row['date_added'])) . "</p>
              </div>
            </div>
          </a>
        </div>";
      }
      echo "</div>";
    } else {
      echo "<p class='px-2'>No products found in " . ucfirst($category) . " category.</p>";
    }
  }
  ?>
</div>

<footer class="mt-5">
  <p>&copy; 2025 Brew Haven | Clint Aldrich Reyes</p>
  <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
