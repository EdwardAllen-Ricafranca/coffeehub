<?php 
session_start();
include('config.php');  // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = $_POST['fullname'];
  $gender = $_POST['gender'];
  $dob = $_POST['dob'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];

  // Address
  $street = $_POST['street'];
  $city = $_POST['city'];
  $province = $_POST['province'];
  $zip = $_POST['zip'];
  $country = $_POST['country'];

  // Account
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  $errors = [];

  // validation
  if (empty($fullname) || !preg_match("/^[a-zA-Z ]{2,50}$/", $fullname)) $errors[] = "Invalid fullname.";
  if (empty($gender)) $errors[] = "Gender is required.";
  if (empty($dob)) $errors[] = "Date of birth is required.";
  else {
    $dobDate = new DateTime($dob);
    $age = (new DateTime())->diff($dobDate)->y;
    if ($age < 18) $errors[] = "You must be at least 18 years old.";
  }
  if (empty($phone) || !preg_match("/^09\d{9}$/", $phone)) $errors[] = "Invalid phone.";
  if (empty($email) || !preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.(com|net|org|gov|edu|ph)$/", $email)) $errors[] = "Invalid email.";
  if (empty($street) || !preg_match("/^[a-zA-Z0-9\s.,#-]{5,100}$/", $street)) $errors[] = "Invalid street.";
  if (empty($city) || !preg_match("/^[a-zA-Z ]{2,50}$/", $city)) $errors[] = "Invalid city.";
  if (empty($province) || !preg_match("/^[a-zA-Z ]{2,50}$/", $province)) $errors[] = "Invalid province.";
  if (empty($zip) || !preg_match("/^\d{4}$/", $zip)) $errors[] = "Invalid zip.";
  if (empty($country) || !preg_match("/^[a-zA-Z ]+$/", $country)) $errors[] = "Invalid country.";
  if (empty($username) || !preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) $errors[] = "Invalid username.";
  if (strlen($password) < 8 ||
      !preg_match("/[A-Z]/", $password) ||
      !preg_match("/[a-z]/", $password) ||
      !preg_match("/\d/", $password) ||
      !preg_match("/[\W_]/", $password)) $errors[] = "Invalid password format.";
  if ($password !== $confirm) $errors[] = "Passwords do not match.";

  if (!empty($errors)) {
    echo "<script>alert('" . implode("\\n", $errors) . "'); window.history.back();</script>";
    exit;
  }

  // Hash the password before storing it
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert into CUSTOMER table
  $stmt = $conn->prepare("INSERT INTO CUSTOMER (FullName, Email, PhoneNumber, ShippingAddress) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $fullname, $email, $phone, $shippingAddress);
  $shippingAddress = "$street, $city, $province, $zip, $country";
  $stmt->execute();

  // Get the last inserted CustID
  $custID = $stmt->insert_id;

  // Insert into USER_LOGIN table
  $stmt = $conn->prepare("INSERT INTO USER_LOGIN (Username, PasswordHash, CustID) VALUES (?, ?, ?)");
  $stmt->bind_param("ssi", $username, $hashedPassword, $custID);
  $stmt->execute();

  // Store user information in session
  $_SESSION['user'] = [
    'fullname' => $fullname,
    'username' => $username,
    'email' => $email
  ];

  // Redirect to login page
  echo "<script>
    alert('Registration successful! Proceeding to login...');
    window.location.href = 'login.php';
  </script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register | Brew Haven</title>
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
      transition: 0.3s;
    }
    .nav-link:hover {
      color: #D6A77A !important;
    }
    .register-container {
      background: #fff;
      border-radius: 12px;
      padding: 40px;
      max-width: 850px;
      margin: 50px auto;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }
    .form-label {
      font-weight: 500;
    }
    h2, h4 {
      color: #4B2E2A;
    }
    .register-btn {
      background-color: #D6A77A;
      color: #fff;
      font-weight: 600;
    }
    .register-btn:hover {
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

<!-- Registration Form -->
<div class="register-container">
  <form action="register.php" method="POST" class="row g-3">
    <h2 class="text-center mb-4"><i class="fas fa-user-plus me-2"></i>Register</h2>

    <div class="col-md-6">
      <label for="fullname" class="form-label">Full Name</label>
      <input type="text" class="form-control" id="fullname" name="fullname" required pattern="^[a-zA-Z ]{2,50}$" title="2–50 letters and spaces only">
    </div>

    <div class="col-md-3">
      <label for="gender" class="form-label">Gender</label>
      <select class="form-select" id="gender" name="gender" required>
        <option selected disabled value="">Choose...</option>
        <option>Male</option>
        <option>Female</option>
        <option>Prefer not to say</option>
      </select>
    </div>

    <div class="col-md-3">
      <label for="dob" class="form-label">Date of Birth</label>
      <input type="date" class="form-control" id="dob" name="dob" required>
    </div>

    <div class="col-md-6">
      <label for="phone" class="form-label">Phone Number</label>
      <input type="tel" class="form-control" id="phone" name="phone" required pattern="^09\d{9}$" title="Starts with 09 and has 11 digits">
    </div>

    <div class="col-md-6">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required
             pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.(com|net|org|gov|edu|ph)$"
             title="Valid email ending in .com, .net, etc.">
    </div>

    <h4 class="pt-4">Address Details</h4>

    <div class="col-12">
      <label for="street" class="form-label">Street</label>
      <input type="text" class="form-control" id="street" name="street" required
             pattern="^[a-zA-Z0-9\s.,#-]{5,100}$" title="5–100 chars, letters, numbers, . , # -">
    </div>

    <div class="col-md-4">
      <label for="city" class="form-label">City</label>
      <input type="text" class="form-control" id="city" name="city" required pattern="^[a-zA-Z ]{2,50}$">
    </div>

    <div class="col-md-4">
      <label for="province" class="form-label">Province</label>
      <input type="text" class="form-control" id="province" name="province" required pattern="^[a-zA-Z ]{2,50}$">
    </div>

    <div class="col-md-2">
      <label for="zip" class="form-label">Zip Code</label>
      <input type="text" class="form-control" id="zip" name="zip" required pattern="^\d{4}$">
    </div>

    <div class="col-md-2">
      <label for="country" class="form-label">Country</label>
      <input type="text" class="form-control" id="country" name="country" required pattern="^[a-zA-Z ]+$">
    </div>

    <h4 class="pt-4">Account Details</h4>

    <div class="col-md-6">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" name="username" required
             pattern="^[a-zA-Z0-9_]{5,20}$">
    </div>

    <div class="col-md-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required
             pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}">
    </div>

    <div class="col-md-3">
      <label for="confirm_password" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>

    <div class="col-12 d-flex gap-3 mt-3">
      <button type="submit" class="register-btn w-50 btn">Register</button>
      <button type="reset" class="btn btn-secondary w-50">Reset</button>
    </div>
  </form>
</div>

<footer>
  <p>&copy; 2025 Brew Haven | Clint Aldrich Reyes</p>
  <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Contact Us</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
