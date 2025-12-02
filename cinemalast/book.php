<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login_user.php'); exit; }

$movie_id = (int)($_GET['id'] ?? 0);
if ($movie_id <= 0) { die('Invalid movie id'); }

// fetch movie
$stmt = $mysqli->prepare("SELECT m.*, t.theater_number, t.location FROM movies m JOIN theaters t ON m.theater_id = t.theater_id WHERE m.movie_id = ?");
$stmt->bind_param('i',$movie_id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$movie) die('Movie not found');

$err = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seats = (int)($_POST['seats'] ?? 0);
    if ($seats <= 0) $err = "Enter a positive number of seats.";
    else {
        // check availability and insert booking in transaction
        $mysqli->begin_transaction();
        try {
            // re-check seats
            $stmt = $mysqli->prepare("SELECT total_seats FROM movies WHERE movie_id = ? FOR UPDATE");
            $stmt->bind_param('i',$movie_id);
            $stmt->execute();
            $r = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $available = (int)$r['total_seats'];
            if ($available < $seats) throw new Exception("Not enough seats. Available: $available");

            // insert booking
            $uid = $_SESSION['user_id'];
            $stmt = $mysqli->prepare("INSERT INTO bookings (user_id, movie_id, seats_booked) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $uid, $movie_id, $seats);
            if (!$stmt->execute()) throw new Exception("Failed to create booking.");
            $stmt->close();

            // decrement seats
            $stmt = $mysqli->prepare("UPDATE movies SET total_seats = total_seats - ? WHERE movie_id = ?");
            $stmt->bind_param('ii', $seats, $movie_id);
            if (!$stmt->execute()) throw new Exception("Failed to update seats.");
            $stmt->close();

            $mysqli->commit();
            $success = "Booked successfully!";
        } catch (Exception $e) {
            $mysqli->rollback();
            $err = $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Book</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="movies.php">Back to Movies</a>
    <a class="button" href="profile.php">Back to Profile</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>Book: <?php echo htmlspecialchars($movie['title']); ?></h2>
  <p>Showtime: <?php echo htmlspecialchars($movie['showtime']); ?> | Price per seat: <?php echo htmlspecialchars($movie['price_per_seat']); ?> | Available: <?php echo (int)$movie['total_seats']; ?></p>

  <?php if($err): ?><div class="error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <form method="post" action="book.php?id=<?php echo $movie_id; ?>">
    <div class="form-row"><label>Seats to book</label><input type="number" name="seats" min="1" max="<?php echo (int)$movie['total_seats']; ?>" required></div>
    <button class="button" type="submit">Book Now</button>
  </form>
</div>
</body>
</html>
