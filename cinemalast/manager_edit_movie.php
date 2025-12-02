<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

$err = $success = '';

$theaters = $mysqli->query("SELECT theater_id, theater_number FROM theaters ORDER BY theater_id");
$movies = $mysqli->query("SELECT * FROM movies ORDER BY movie_id");

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit/Delete Movies</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">

  <div class="nav">
    <a class="button" href="manager_dashboard.php">Back to Dashboard</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>Edit or Delete Movies</h2>
  <table>
    <tr><th>ID</th><th>Title</th><th>Edit</th><th>Delete</th></tr>
    <?php while($m = $movies->fetch_assoc()): ?>
    <tr>
      <td><?php echo $m['movie_id']; ?></td>
      <td><?php echo htmlspecialchars($m['title']); ?></td>
      <td>
        <a class="button" href="manager_edit_movie_form.php?id=<?php echo $m['movie_id']; ?>">Edit</a>
      </td>
      <td>
        <a class="button secondary" href="manager_delete_movie.php?id=<?php echo $m['movie_id']; ?>">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

</div>
</body>
</html>
