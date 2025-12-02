<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

if (isset($_GET['approve'])) {
    $uid = (int)$_GET['approve'];
    $stmt = $mysqli->prepare("UPDATE users SET is_approved = 1 WHERE user_id = ?");
    $stmt->bind_param('i',$uid);
    $stmt->execute();
    $stmt->close();
    header('Location: manager_approve.php');
    exit;
}

// list unapproved
$res = $mysqli->query("SELECT user_id, name, email, phone FROM users WHERE is_approved = 0 ORDER BY user_id ASC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Approve Users</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <div class="nav">
    <a class="button" href="manager_dashboard.php">Back to Dashboard</a>
    <a class="button" href="logout.php">Logout</a>
  </div>
  <h2>Unapproved Registrations</h2>
  <table>
    <tr><th>Name</th><th>Email</th><th>Phone</th><th>Action</th></tr>
    <?php while($u = $res->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($u['name']); ?></td>
      <td><?php echo htmlspecialchars($u['email']); ?></td>
      <td><?php echo htmlspecialchars($u['phone']); ?></td>
      <td><a class="button" href="manager_approve.php?approve=<?php echo $u['user_id']; ?>">Approve</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
