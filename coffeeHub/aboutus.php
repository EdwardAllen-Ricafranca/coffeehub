<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us | Brew Haven</title>
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
      font-size: 30px;
      font-weight: bold;
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
      font-size: 1.1rem;
    }

    .nav-item:hover {
      background-color: #D6A77A;
    }

    .header-section {
      background: linear-gradient(135deg, #6B4F4F 0%, #A9746E 100%);
      color: white;
      padding: 80px 0;
      text-align: center;
    }

    .header-section h1 {
      font-size: 3rem;
      font-weight: bold;
    }

    .about-content {
      padding: 60px 0;
    }

    .about-text {
      font-size: 1.1rem;
      color: #4B2E2A;
    }

    .contact-info {
      background-color: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }

    .contact-info h4 {
      color: #4E342E;
      font-weight: bold;
    }

    .contact-info p {
      color: #333;
      font-size: 1rem;
      margin-bottom: 10px;
    }

    .form-control, .btn {
      border-radius: 8px;
    }

    .btn-send {
      background-color: #D6A77A;
      color: white;
      font-weight: 500;
    }

    .btn-send:hover {
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
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2"  href="index.php">
      <img src="images/logo.png" alt="Brew Haven Logo">
      <span>Coffee Hub</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto gap-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="whatweoffer.php">What We Offer</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <a class="nav-link" href="cart.php">
            <i class="fas fa-shopping-cart me-1"></i> Cart
        </a>
      </ul>
    </div>
  </div>
</nav>

<!-- Header -->
<div class="header-section">
  <div class="container">
    <h1>About Coffee Hub</h1>
    <p>Crafting warm moments, one cup at a time.</p>
  </div>
</div>

<!-- About Content -->
<div class="container about-content">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="about-text mb-5">
        <p>
          Coffee Hub was founded with one goal: to bring the perfect cup of coffee to your hands, wherever you are. We believe coffee is more than just a drink—it’s a moment, an experience, and a connection.
        </p>
        <p>
          From ethically sourced beans to carefully selected brewing gear, we’re here to serve both coffee lovers and newcomers alike. Whether you're brewing from home or exploring our cold brews, you’ll feel the warmth of Brew Haven in every sip.
        </p>
      </div>

      <!-- Contact Info -->
      <div class="contact-info mb-5">
        <h4><i class="fas fa-envelope"></i> Contact Us</h4>
        <p><strong>Email:</strong> support@coffeehub.com</p>
        <p><strong>Phone:</strong> +63 912 345 6789</p>
        <p>We typically respond within 24 hours during weekdays.</p>
      </div>

      <!-- Contact Form -->
      <form>
        <div class="row g-3">
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Your Name" required>
          </div>
          <div class="col-md-6">
            <input type="email" class="form-control" placeholder="Your Email" required>
          </div>
          <div class="col-12">
            <input type="text" class="form-control" placeholder="Subject" required>
          </div>
          <div class="col-12">
            <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
          </div>
          <div class="col-12 text-end">
            <button type="submit" class="btn btn-send px-4">Send Message</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <p>&copy; 2025 Brew Haven | Clint Aldrich Reyes</p>
  <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
