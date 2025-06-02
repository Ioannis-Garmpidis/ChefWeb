<?php
session_start();

// ===== Έλεγχος login =====
if (!isset($_SESSION['user_id'])) {
    exit();
}

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;

// ===== Έλεγχος παραμέτρων =====
if (isset($_POST['recipe_id']) && !empty(trim($_POST['comment']))) {
    $user_id = $_SESSION['user_id'];
    $recipe_id = intval($_POST['recipe_id']);
    $comment = trim($_POST['comment']);

    // ===== Εισαγωγή σχολίου στη βάση =====
    $stmt = $mysqli->prepare("INSERT INTO comments (user_id, recipe_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $recipe_id, $comment);
    $stmt->execute();
    $stmt->close();
}

$mysqli->close();

// ===== Επιστροφή στην προηγούμενη σελίδα =====
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>