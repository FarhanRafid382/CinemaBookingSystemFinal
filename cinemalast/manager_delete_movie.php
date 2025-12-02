<?php
session_start();
require 'db.php';
if (!isset($_SESSION['manager_id'])) { header('Location: login_manager.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $mysqli->prepare("DELETE FROM movies WHERE movie_id=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $stmt->close();
}
header('Location: manager_edit_movie.php');
exit;
