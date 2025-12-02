<?php
session_start();
require 'db.php';

// fetch movies with theater info and average score
$sql = "SELECT m.*, t.theater_number, t.location,
           (SELECT ROUND(AVG(s.score),2) FROM scores s WHERE s.movie_id = m.movie_id) AS avg_score
        FROM movies m
        JOIN theaters t ON m.theater_id = t.theater_id
        ORDER BY m.showtime ASC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Movies</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="index.php">Home</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a class="button" href="profile.php">Profile</a>
      <a class="button" href="logout.php">Logout</a>
    <?php else: ?>
      <a class="button" href="login_user.php">Member Login</a>
    <?php endif; ?>
    <?php if(isset($_SESSION['manager_id'])): ?>
      <a class="button" href="manager_dashboard.php">Manager Dashboard</a>
      <a class="button" href="logout.php">Logout</a>
    <?php endif; ?>
  </div>

  <h2>Available Movies</h2>
  <table>
    <tr>
      <th>Title</th><th>Showtime</th><th>Release Date</th><th>Price</th><th>Available Seats</th>
      <th>Theater</th><th>Location</th><th>Genre</th><th>Duration</th><th>Avg Score</th><th>Actions</th>
    </tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['showtime']); ?></td>
        <td><?php echo htmlspecialchars($row['release_date']); ?></td>
        <td><?php echo htmlspecialchars($row['price_per_seat']); ?></td>
        <td><?php echo (int)$row['total_seats']; ?></td>
        <td><?php echo htmlspecialchars($row['theater_number']); ?></td>
        <td><?php echo htmlspecialchars($row['location']); ?></td>
        <td><?php echo htmlspecialchars($row['genre']); ?></td>
        <td><?php echo htmlspecialchars($row['duration']); ?></td>
        <td><?php echo $row['avg_score'] ?? '-'; ?></td>
        <td>
          <a class="button" href="movie_details.php?id=<?php echo $row['movie_id']; ?>">Details</a>
          <?php if(isset($_SESSION['user_id'])): ?>
            <a class="button" href="book.php?id=<?php echo $row['movie_id']; ?>">Book</a>
            <a class="button" href="submit_score.php?id=<?php echo $row['movie_id']; ?>">Score</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
