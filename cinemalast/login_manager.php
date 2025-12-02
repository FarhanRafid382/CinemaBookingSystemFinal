<?php
session_start();
require 'db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($email === '' || $password === '') $err = "Email and password required.";
    else {
        $stmt = $mysqli->prepare("SELECT manager_id, name, password FROM manager WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if ($row['password'] === $password) {
                $_SESSION['manager_id'] = $row['manager_id'];
                $_SESSION['manager_name'] = $row['name'];
                header('Location: manager_dashboard.php'); exit;
            } else $err = "Invalid credentials.";
        } else $err = "Invalid credentials.";
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manager Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <h2>Manager Login</h2>
  <?php if($err): ?><div class="error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <form method="post" action="login_manager.php">
    <div class="form-row"><label>Email</label><input type="email" name="email" required></div>
    <div class="form-row"><label>Password</label><input type="password" name="password" required></div>
    <button class="button" type="submit">Login</button>
    <a class="button secondary" href="index.php">Back</a>
  </form>
</div>
</body>
</html>
