<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

$sql = "SELECT b.booking_id, b.seats_booked, b.booking_date,
               u.name AS user_name, u.email,
               m.title AS movie_title
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN movies m ON b.movie_id = m.movie_id
        ORDER BY b.booking_date DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>View Bookings</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="manager_dashboard.php">Back to Dashboard</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>All Bookings</h2>
  <table>
    <tr>
      <th>User</th><th>Email</th><th>Movie</th><th>Seats</th><th>Date</th>
    </tr>
    <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['user_name']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo htmlspecialchars($row['movie_title']); ?></td>
      <td><?php echo (int)$row['seats_booked']; ?></td>
      <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
