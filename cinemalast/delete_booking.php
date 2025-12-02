<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login_user.php'); exit; }
$uid = $_SESSION['user_id'];
$booking_id = (int)($_GET['id'] ?? 0);
if ($booking_id <= 0) { header('Location: profile.php'); exit; }

// fetch booking and ensure it belongs to user
$stmt = $mysqli->prepare("SELECT movie_id, seats_booked FROM bookings WHERE booking_id = ? AND user_id = ?");
$stmt->bind_param('ii', $booking_id, $uid);
$stmt->execute();
$res = $stmt->get_result();
$booking = $res->fetch_assoc();
$stmt->close();
if (!$booking) { header('Location: profile.php'); exit; }

$movie_id = (int)$booking['movie_id'];
$seats = (int)$booking['seats_booked'];

// perform deletion and restore seats in transaction
$mysqli->begin_transaction();
try {
    $stmt = $mysqli->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->bind_param('i', $booking_id);
    if (!$stmt->execute()) throw new Exception("Failed to delete booking");
    $stmt->close();

    $stmt = $mysqli->prepare("UPDATE movies SET total_seats = total_seats + ? WHERE movie_id = ?");
    $stmt->bind_param('ii', $seats, $movie_id);
    if (!$stmt->execute()) throw new Exception("Failed to restore seats");
    $stmt->close();

    $mysqli->commit();
} catch (Exception $e) {
    $mysqli->rollback();
}
header('Location: profile.php');
exit;
