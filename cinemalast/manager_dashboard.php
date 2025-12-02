<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Manager Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="index.php">Home</a>
    <a class="button" href="logout.php">Logout</a>
  </div>
  <h2>Manager Dashboard</h2>
  <p>Welcome <?php echo htmlspecialchars($_SESSION['manager_name']); ?></p>
  <ul>
    <li><a class="button" href="manager_add_movie.php">Add Movie</a></li>
    <li><a class="button" href="manager_edit_movie.php">Edit/Delete Movies</a></li>
    <li><a class="button" href="manager_theaters.php">Manage Theaters</a></li>
    <li><a class="button" href="manager_view_bookings.php">View Bookings</a></li>
    <li><a class="button" href="manager_view_scores.php">View Scores</a></li>
    <li><a class="button" href="manager_approve.php">Approve Users</a></li>
  </ul>
</div>
</body>
</html>
