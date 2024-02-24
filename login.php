<?php 

require 'includes/db.php'; 

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="images/warehouse.png">
  <title>CryptoVault - Login</title>
  <link rel="stylesheet" href="./css/login.css">
</head>
<body>
  <div class="container">
    <div class="login-container">
      <form class="login-form" action="login.php" method="POST">
        <h2>Login Area</h2>
        <div class="input-field">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="input-field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <input type="submit" class="login-btn" value="Login" name="login">
      </form>
    </div>
    <div class="right-content">
      <h2>Welcome to CryptoVault</h2>
      <p>Your secure gateway to managing and safeguarding your valuable cryptocurrency mining assets. With our intuitive login interface, gain access to powerful tools designed to organize, and protect your ASIC machines. Whether you're a seasoned or just starting out, CryptoVault ensures that your storage data remains secure, organized, and readily accessible, empowering you to optimize your mining efficiency and maximize your returns.</p>
    </div>
  </div>
</body>
</html>

<?php

if(isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])){
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $match_query = mysqli_query($db, "SELECT * FROM users WHERE username = '$username' and password = '$password';");
  $fetch_mq = mysqli_fetch_assoc($match_query);
  $user = $fetch_mq['username'];
  $pass = $fetch_mq['password'];

  if($username == $user && $password == $pass){
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit();
  }else{
    echo "Invalid username or password";
  }
}

?>

