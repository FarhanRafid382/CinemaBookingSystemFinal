<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die('Invalid movie');

$err = $success = '';

// fetch movie
$stmt = $mysqli->prepare("SELECT * FROM movies WHERE movie_id=?");
$stmt->bind_param('i',$id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$movie) die('Movie not found');

// fetch theaters
$theaters = $mysqli->query("SELECT theater_id, theater_number FROM theaters ORDER BY theater_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $showtime = trim($_POST['showtime']);
    $release_date = trim($_POST['release_date']);
    $price = (float)$_POST['price'];
    $seats = (int)$_POST['seats'];
    $theater_id = (int)$_POST['theater_id'];
    $genre = trim($_POST['genre']);
    $duration = trim($_POST['duration']);

    if ($title === '' || $showtime === '' || $theater_id <= 0) {
        $err = "Fields required.";
    } else {
        $stmt = $mysqli->prepare("UPDATE movies SET
                                 title=?, showtime=?, release_date=?, price_per_seat=?, total_seats=?, theater_id=?, genre=?, duration=?
                                 WHERE movie_id=?");
        $stmt->bind_param('ssdiisssi', $title, $showtime, $release_date, $price, $seats, $theater_id, $genre, $duration, $id);
        if ($stmt->execute()) {
            $success = "Movie updated.";
        } else $err = "Failed to update.";
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Movie</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">

  <div class="nav">
    <a class="button" href="manager_edit_movie.php">Back</a>
    <a class="button" href="manager_dashboard.php">Dashboard</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>Edit Movie</h2>
  <?php if($err): ?><div class="error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <form method="post">
    <div class="form-row"><label>Title</label><input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>"></div>
    <div class="form-row"><label>Showtime</label><input type="text" name="showtime" value="<?php echo htmlspecialchars($movie['showtime']); ?>"></div>
    <div class="form-row"><label>Release Date</label><input type="date" name="release_date" value="<?php echo htmlspecialchars($movie['release_date']); ?>"></div>
    <div class="form-row"><label>Price</label><input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($movie['price_per_seat']); ?>"></div>
    <div class="form-row"><label>Total Seats</label><input type="number" name="seats" value="<?php echo htmlspecialchars($movie['total_seats']); ?>"></div>

    <div class="form-row"><label>Theater</label>
      <select name="theater_id">
        <?php while($t = $theaters->fetch_assoc()): ?>
        <option value="<?php echo $t['theater_id']; ?>" 
          <?php if($t['theater_id']==$movie['theater_id']) echo 'selected'; ?>>
          <?php echo htmlspecialchars($t['theater_number']); ?>
        </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="form-row"><label>Genre</label><input type="text" name="genre" value="<?php echo htmlspecialchars($movie['genre']); ?>"></div>
    <div class="form-row"><label>Duration</label><input type="text" name="duration" value="<?php echo htmlspecialchars($movie['duration']); ?>"></div>

    <button class="button" type="submit">Save</button>
  </form>

</div>
</body>
</html>
