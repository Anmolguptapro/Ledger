<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ledger</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    header {
      background-color: #2196F3;
      padding: 1px;
      text-align: center;
    }

    nav {
      display: flex;
      justify-content: center;
      flex-direction: row;
      align-items: center;
      background-color: #09b2eb;
      margin-bottom: 11px;
    }

    nav a {
      color: white;
      text-decoration: none;
      padding: 14px 20px;
      text-align: center;

    }

    nav a:hover {
      background-color: #ddd;
      color: black;
    }

  </style>
</head>

<body>
  <header>
    <h1>Ledger</h1>
  </header>
  <nav>
    <a href="index.php">Home</a>
    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
    <a href="registration.php">registration</a>
    <a href="login.php">Login</a>
  </nav>
  <!-- Add the rest of your webpage content here -->
  <?php
  $e_verify = true;
  $p_verify = true;
  @session_start();
  if (!isset($_SESSION["login_status"])) {
    $_SESSION["login_status"] = false;
  } else {
    if ($_SESSION["login_status"]) {
      if ($_SESSION["login_pv"] !== 'Y') {
        $p_verify = false;
      }

      if ($_SESSION["login_ev"] !== 'Y') {
        $e_verify = false;
      }
    }
  }
  ?>
  <base href="http://localhost/ledger/">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

</body>

</html>