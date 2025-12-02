<?php
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cinema Booking System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Cinema Booking System</h1>
  <div class="nav">
    <a class="button" href="movies.php">Browse Movies</a>
    <a class="button" href="register.php">Register</a>
    <a class="button" href="login_user.php">Member Login</a>
    <a class="button" href="login_manager.php">Manager Login</a>
  </div>

  <?php if(isset($_SESSION['user_id'])): ?>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['user_name']); ?> |
      <a href="profile.php">Profile</a> |
      <a href="logout.php">Logout</a></p>
  <?php endif; ?>

  <p>Use manager credentials: <strong>manager@cinema.com / managerpass</strong> (sample)</p>
</div>
</body>
</html>
