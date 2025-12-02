<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login_user.php'); exit; }
$uid = $_SESSION['user_id'];

// user info
$stmt = $mysqli->prepare("SELECT name, phone, email FROM users WHERE user_id = ?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// bookings
$stmt = $mysqli->prepare("SELECT b.booking_id, b.seats_booked, b.booking_date, m.title FROM bookings b JOIN movies m ON b.movie_id = m.movie_id WHERE b.user_id = ? ORDER BY b.booking_date DESC");
$stmt->bind_param('i', $uid);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();

// scores
$stmt = $mysqli->prepare("SELECT s.score_id, s.score, s.score_date, m.title, m.movie_id FROM scores s JOIN movies m ON s.movie_id = m.movie_id WHERE s.user_id = ? ORDER BY s.score_date DESC");
$stmt->bind_param('i', $uid);
$stmt->execute();
$scores = $stmt->get_result();
$stmt->close();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Profile</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="movies.php">Back to Movies</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>Profile</h2>
  <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?> | <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?> | <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>

  <h3>My Bookings</h3>
  <table>
    <tr><th>Movie</th><th>Seats</th><th>Date</th><th>Action</th></tr>
    <?php while($b = $bookings->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($b['title']); ?></td>
      <td><?php echo (int)$b['seats_booked']; ?></td>
      <td><?php echo htmlspecialchars($b['booking_date']); ?></td>
      <td><a class="button secondary" href="delete_booking.php?id=<?php echo $b['booking_id']; ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <h3>My Scores</h3>
  <table>
    <tr><th>Movie</th><th>Score</th><th>Date</th><th>Actions</th></tr>
    <?php while($s = $scores->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($s['title']); ?></td>
      <td><?php echo (int)$s['score']; ?></td>
      <td><?php echo htmlspecialchars($s['score_date']); ?></td>
      <td>
        <a class="button" href="submit_score.php?id=<?php echo $s['movie_id']; ?>">Edit</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
