<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login_user.php'); exit; }
$uid = $_SESSION['user_id'];
$movie_id = (int)($_GET['id'] ?? 0);
if ($movie_id <= 0) die('Invalid movie');

$err = $success = '';
// fetch existing score by user for this movie
$stmt = $mysqli->prepare("SELECT score_id, score FROM scores WHERE user_id = ? AND movie_id = ?");
$stmt->bind_param('ii',$uid,$movie_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete']) && $existing) {
        $stmt = $mysqli->prepare("DELETE FROM scores WHERE score_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $existing['score_id'], $uid);
        $stmt->execute();
        $stmt->close();
        $success = "Score deleted.";
        header('Location: profile.php'); exit;
    }

    $score = (int)($_POST['score'] ?? 0);
    if ($score < 1 || $score > 100) $err = "Score must be between 1 and 100.";
    else {
        if ($existing) {
            $stmt = $mysqli->prepare("UPDATE scores SET score = ?, score_date = NOW() WHERE score_id = ? AND user_id = ?");
            $stmt->bind_param('iii',$score, $existing['score_id'], $uid);
            if ($stmt->execute()) $success = "Score updated.";
            else $err = "Failed to update.";
            $stmt->close();
        } else {
            $stmt = $mysqli->prepare("INSERT INTO scores (user_id, movie_id, score) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $uid, $movie_id, $score);
            if ($stmt->execute()) $success = "Score submitted.";
            else $err = "Failed to submit. You may already have scored this movie.";
            $stmt->close();
        }
        header('Location: movies.php'); exit;
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Submit Score</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="movies.php">Back to Movies</a>
    <a class="button" href="profile.php">Back to Profile</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>Submit Score</h2>
  <?php if($err): ?><div class="error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <form method="post" action="submit_score.php?id=<?php echo $movie_id; ?>">
    <div class="form-row"><label>Score (1-100)</label>
      <input type="number" name="score" min="1" max="100" required value="<?php echo $existing['score'] ?? ''; ?>"></div>
    <button class="button" type="submit">Submit</button>
    <?php if($existing): ?>
      <button class="button secondary" type="submit" name="delete" value="1">Delete My Score</button>
    <?php endif; ?>
  </form>
</div>
</body>
</html>
