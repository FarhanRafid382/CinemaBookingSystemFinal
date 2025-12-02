<?php
session_start();
require 'db.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        $err = "Name, email and password required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Invalid email.";
    } else {
        // insert with is_approved = 0
        $stmt = $mysqli->prepare("INSERT INTO users (name, phone, email, password, is_approved) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param('ssss', $name, $phone, $email, $password);
        if ($stmt->execute()) {
            $success = "Registered. Awaiting manager approval.";
        } else {
            $err = "Registration failed. Email may already exist.";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
  <h2>User Registration</h2>
  <?php if(!empty($err)): ?><div class="error"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if(!empty($success)): ?><div class="notice"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
  <form method="post" action="register.php">
    <div class="form-row"><label>Name</label><input type="text" name="name" required></div>
    <div class="form-row"><label>Phone</label><input type="text" name="phone"></div>
    <div class="form-row"><label>Email</label><input type="email" name="email" required></div>
    <div class="form-row"><label>Password</label><input type="password" name="password" required></div>
    <button class="button" type="submit">Register</button>
    <a class="button secondary" href="index.php">Back</a>
  </form>
</div>
</body>
</html>
