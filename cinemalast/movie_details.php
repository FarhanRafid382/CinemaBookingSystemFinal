<?php
session_start();
require 'db.php';

$movie_id = (int)($_GET['id'] ?? 0);
if ($movie_id <= 0) die('Invalid movie');

$stmt = $mysqli->prepare("SELECT m.*, t.theater_number, t.location FROM movies m
                          JOIN theaters t ON m.theater_id = t.theater_id
                          WHERE movie_id=?");
$stmt->bind_param('i',$movie_id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$movie) die('Movie not found');

$scores = $mysqli->prepare("SELECT s.score, s.score_date, u.name AS user_name
                            FROM scores s
                            JOIN users u ON s.user_id = u.user_id
                            WHERE s.movie_id = ?
                            ORDER BY s.score_date DESC");
$scores->bind_param('i',$movie_id);
$scores->execute();
$score_list = $scores->get_result();
$scores->close();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Movie Details</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  
  <div class="nav">
    <a class="button" href="movies.php">Back to Movies</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a class="button" href="profile.php">Profile</a>
      <a class="button" href="logout.php">Logout</a>
    <?php endif; ?>

    <?php if(isset($_SESSION['manager_id'])): ?>
      <a class="button" href="manager_dashboard.php">Dashboard</a>
      <a class="button" href="logout.php">Logout</a>
    <?php endif; ?>
  </div>

  <h2><?php echo htmlspecialchars($movie['title']); ?></h2>

  <p><strong>Showtime:</strong> <?php echo htmlspecialchars($movie['showtime']); ?></p>
  <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
  <p><strong>Price:</strong> <?php echo htmlspecialchars($movie['price_per_seat']); ?></p>
  <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($movie['total_seats']); ?></p>
  <p><strong>Theater:</strong> <?php echo htmlspecialchars($movie['theater_number']); ?> /
     <?php echo htmlspecialchars($movie['location']); ?></p>
  <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
  <p><strong>Duration:</strong> <?php echo htmlspecialchars($movie['duration']); ?></p>

  <hr>

  <h3>User Scores</h3>
  <table>
    <tr><th>User</th><th>Score</th><th>Date</th></tr>
    <?php while($s = $score_list->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($s['user_name']); ?></td>
      <td><?php echo (int)$s['score']; ?></td>
      <td><?php echo htmlspecialchars($s['score_date']); ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <?php if(isset($_SESSION['user_id'])): ?>
    <a class="button" href="submit_score.php?id=<?php echo $movie_id; ?>">Submit / Edit My Score</a>
    <a class="button" href="book.php?id=<?php echo $movie_id; ?>">Book</a>
  <?php endif; ?>

</div>
</body>
</html>
