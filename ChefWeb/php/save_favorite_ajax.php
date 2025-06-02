<?php
session_start();
header('Content-Type: application/json');

// ===== Έλεγχος login =====
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'not_logged_in']);
    exit();
}

if (!isset($_POST['recipe_id']) || !is_numeric($_POST['recipe_id'])) {
    echo json_encode(['success' => false, 'message' => 'invalid_request']);
    exit();
}

$recipe_id = intval($_POST['recipe_id']);
$user_id = $_SESSION['user_id'];


// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;

// ===== Έλεγχος αν υπάρχει ήδη =====
$stmt = $mysqli->prepare("SELECT id FROM favorites WHERE user_id = ? AND recipe_id = ?");
$stmt->bind_param("ii", $user_id, $recipe_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    // Δεν υπάρχει – το προσθέτουμε
    $stmt->close();
    $stmt_insert = $mysqli->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)");
    $stmt_insert->bind_param("ii", $user_id, $recipe_id);
    $stmt_insert->execute();
    $stmt_insert->close();
    echo json_encode(['success' => true, 'message' => 'added']);
} else {
    // Υπάρχει – το αφαιρούμε
    $stmt->close();
    $stmt_delete = $mysqli->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?");
    $stmt_delete->bind_param("ii", $user_id, $recipe_id);
    $stmt_delete->execute();
    $stmt_delete->close();
    echo json_encode(['success' => true, 'message' => 'removed']);
}

$mysqli->close();