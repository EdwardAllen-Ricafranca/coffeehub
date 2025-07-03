<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome to Brew Haven</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fef9f5;
    }

    /* Navbar */
    nav {
      background-color: #4B2E2A;
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
      color: #D6A77A !important;
    }

    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, #6B4F4F 0%, #A9746E 100%);
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
    .hero-section .btn {
      background-color: #D6A77A;
      border: none;
      padding: 12px 30px;
      font-size: 1.1rem;
      margin-top: 20px;
    }
    .hero-section .btn:hover {
      background-color: #c28e67;
    }

    /* Promotions Section */
    .promotions-section {
      background-color: #fff8f0;
      padding: 60px 0;
    }
    .promo-title {
      text-align: center;
      margin-bottom: 40px;
      color: #4B2E2A;
    }
    .promo-card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
    }
    .promo-card:hover {
      transform: translateY(-5px);
    }
    .promo-card img {
      width: 100%;
      height: 200px;
      object-fit: contain;
    }

    /* Footer */
    footer {
      background-color: #4B2E2A;
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
<nav class="navbar navbar-expand-lg navbar-dark py-3" style="background-color: #4E342E;">
  <div class="container">
    <!-- Logo and Brand -->
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.html">
      <img src="images/logo.png" alt="Brew Haven Logo" width="60" height="60" style="object-fit: contain; border-radius: 50%; float: left;">
      <span style="font-weight: bold; font-size: 2.0rem;">Coffee Hub</span>
    </a>

    <!-- Toggle button for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navigation Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto gap-3">
        
        <li class="nav-item"><a class="nav-link text-white" href="whatweoffer.php">What We Offer</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="account.php">Account</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="aboutus.php">About Us</a></li>
        
        <li class="nav-item">
          <a class="nav-link text-white" href="cart.php">
            <i class="fas fa-shopping-cart me-1"></i> Cart
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


  <!-- Hero Section -->
  <div class="hero-section">
    <div class="container">
      <h1>Start Your Day with a Perfect Brew</h1>
      <p>Discover handpicked coffee beans, artisan blends, and brewing gear made for coffee lovers.</p>
      <a href="whatweoffer.php" class="btn btn-warning">Explore Our Brews</a>
    </div>
  </div>

  <!-- Promotions / Featured Products Section -->
  <section class="promotions-section">
    <div class="container">
      <h2 class="promo-title">☕ Featured Coffee Picks</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card promo-card">
            <img src="images/promotion.jpg" alt="Arabica Coffee Beans">
            <div class="card-body text-center">
              <h5 class="card-title">100% Arabica Beans</h5>
              <p class="card-text">Freshly roasted beans for the perfect morning brew.</p>
              <a href="whatweoffer.html" class="btn btn-sm btn-warning">Shop Now</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card promo-card">
            <img src="images/promotion.jpg" alt="French Press">
            <div class="card-body text-center">
              <h5 class="card-title">Classic French Press</h5>
              <p class="card-text">Brew like a barista with this timeless brewing tool.</p>
              <a href="whatweoffer.html" class="btn btn-sm btn-warning">Buy One</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card promo-card">
            <img src="images/promotion.jpg" alt="Coffee Mugs">
            <div class="card-body text-center">
              <h5 class="card-title">Handcrafted Coffee Mugs</h5>
              <p class="card-text">Sip in style with our cozy, ceramic mugs made with love.</p>
              <a href="whatweoffer.html" class="btn btn-sm btn-warning">View Collection</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Brew Haven | Clint Aldrich Reyes</p>
    <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
