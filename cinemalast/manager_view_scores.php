<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

$sql = "SELECT s.score_id, s.score, s.score_date,
               u.name AS user_name, u.email,
               m.title AS movie_title
        FROM scores s
        JOIN users u ON s.user_id = u.user_id
        JOIN movies m ON s.movie_id = m.movie_id
        ORDER BY s.score_date DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>View Scores</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="manager_dashboard.php">Back to Dashboard</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>All Scores</h2>
  <table>
    <tr>
      <th>User</th><th>Email</th><th>Movie</th><th>Score</th><th>Date</th>
    </tr>
    <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['user_name']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo htmlspecialchars($row['movie_title']); ?></td>
      <td><?php echo (int)$row['score']; ?></td>
      <td><?php echo htmlspecialchars($row['score_date']); ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
