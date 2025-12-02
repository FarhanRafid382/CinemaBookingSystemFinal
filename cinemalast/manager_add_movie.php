<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

$err = $success = '';
// fetch theaters for dropdown
$theaters = $mysqli->query("SELECT theater_id, theater_number FROM theaters ORDER BY theater_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $showtime = trim($_POST['showtime'] ?? '');
    $release_date = trim($_POST['release_date'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $seats = (int)($_POST['seats'] ?? 0);
    $theater_id = (int)($_POST['theater_id'] ?? 0);
    $genre = trim($_POST['genre'] ?? '');
    $duration = trim($_POST['duration'] ?? '');

    if ($title === '' || $showtime === '' || $theater_id <= 0) $err = "Title, showtime and theater required.";
    else {
        $stmt = $mysqli->prepare("INSERT INTO movies (title, showtime, release_date, price_per_seat, total_seats, theater_id, genre, duration) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssdiisss', $title, $showtime, $release_date, $price, $seats, $theater_id, $genre, $duration);
        if ($stmt->execute()) {
            $success = "Movie added.";
        } else $err = "Failed to add movie.";
        $stmt->close();
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Add Movie</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="manager_dashboard.php">Back to Dashboard</a>
    <a class="button" href="logout.php">Logout</a>
  </div>
  <h2>Add Movie</h2>
  <?php if($err): ?><div class="error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
  <form method="post" action="manager_add_movie.php">
    <div class="form-row"><label>Title</label><input type="text" name="title" required></div>
    <div class="form-row"><label>Showtime (YYYY-MM-DD HH:MM:SS)</label><input type="text" name="showtime" required></div>
    <div class="form-row"><label>Release Date</label><input type="date" name="release_date"></div>
    <div class="form-row"><label>Price per Seat</label><input type="number" step="0.01" name="price" required></div>
    <div class="form-row"><label>Total Seats</label><input type="number" name="seats" required></div>
    <div class="form-row"><label>Theater</label>
      <select name="theater_id" required>
        <option value="">-- select --</option>
        <?php while($t = $theaters->fetch_assoc()): ?>
          <option value="<?php echo $t['theater_id']; ?>"><?php echo htmlspecialchars($t['theater_number']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="form-row"><label>Genre</label><input type="text" name="genre"></div>
    <div class="form-row"><label>Duration</label><input type="text" name="duration"></div>
    <button class="button" type="submit">Add Movie</button>
  </form>
</div>
</body>
</html>
