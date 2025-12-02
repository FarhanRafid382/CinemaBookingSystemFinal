<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

$err = $success = '';

// add theater
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $num = trim($_POST['theater_number'] ?? '');
    $loc = trim($_POST['location'] ?? '');
    if ($num === '' || $loc === '') {
        $err = "Fields required.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO theaters (theater_number, location) VALUES (?,?)");
        $stmt->bind_param('ss',$num,$loc);
        if ($stmt->execute()) $success = "Theater added.";
        else $err = "Failed to add.";
        $stmt->close();
    }
}

// edit theater
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = (int)$_POST['theater_id'];
    $num = trim($_POST['theater_number']);
    $loc = trim($_POST['location']);
    if ($num === '' || $loc === '') {
        $err = "Fields required.";
    } else {
        $stmt = $mysqli->prepare("UPDATE theaters SET theater_number=?, location=? WHERE theater_id=?");
        $stmt->bind_param('ssi',$num,$loc,$id);
        if ($stmt->execute()) $success = "Theater updated.";
        else $err = "Failed to update.";
        $stmt->close();
    }
}

// delete theater
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM theaters WHERE theater_id=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $stmt->close();
    header('Location: manager_theaters.php'); exit;
}

$theaters = $mysqli->query("SELECT * FROM theaters ORDER BY theater_id ASC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Theaters</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="manager_dashboard.php">Back to Dashboard</a>
    <a class="button" href="logout.php">Logout</a>
  </div>

  <h2>Manage Theaters</h2>
  <?php if($err): ?><div class="error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($success): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

  <h3>Add Theater</h3>
  <form method="post">
    <input type="hidden" name="add" value="1">
    <div class="form-row"><label>Theater Number</label><input type="text" name="theater_number"></div>
    <div class="form-row"><label>Location</label><input type="text" name="location"></div>
    <button class="button" type="submit">Add</button>
  </form>

  <h3>Existing Theaters</h3>
  <table>
    <tr><th>ID</th><th>Theater</th><th>Location</th><th>Edit</th><th>Delete</th></tr>
    <?php while($t = $theaters->fetch_assoc()): ?>
    <tr>
      <td><?php echo $t['theater_id']; ?></td>
      <td><?php echo htmlspecialchars($t['theater_number']); ?></td>
      <td><?php echo htmlspecialchars($t['location']); ?></td>
      <td>
        <form method="post">
          <input type="hidden" name="edit" value="1">
          <input type="hidden" name="theater_id" value="<?php echo $t['theater_id']; ?>">
          <input type="text" name="theater_number" value="<?php echo htmlspecialchars($t['theater_number']); ?>">
          <input type="text" name="location" value="<?php echo htmlspecialchars($t['location']); ?>">
          <button class="button" type="submit">Save</button>
        </form>
      </td>
      <td><a class="button secondary" href="manager_theaters.php?delete=<?php echo $t['theater_id']; ?>">Delete</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
