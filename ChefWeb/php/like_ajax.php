<?php
// ===== Ξεκινά η συνεδρία =====
session_start();
header('Content-Type: application/json');

// ===== Αν δεν είναι συνδεδεμένος ο χρήστης =====
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'not_logged_in']);
    exit();
}

// ===== Έλεγχος εγκυρότητας POST =====
if (!isset($_POST['recipe_id']) || !is_numeric($_POST['recipe_id'])) {
    echo json_encode(['success' => false, 'message' => 'invalid_request']);
    exit();
}

$recipe_id = intval($_POST['recipe_id']);
$user_id = $_SESSION['user_id'];

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;

// ===== Έλεγχος αν υπάρχει ήδη like =====
$stmt = $mysqli->prepare("SELECT id FROM likes WHERE user_id = ? AND recipe_id = ?");
$stmt->bind_param("ii", $user_id, $recipe_id);
$stmt->execute();
$stmt->store_result();
$has_liked = $stmt->num_rows > 0;
$stmt->close();

// ===== Εισαγωγή ή διαγραφή του like =====
if ($has_liked) {
    $stmt = $mysqli->prepare("DELETE FROM likes WHERE user_id = ? AND recipe_id = ?");
} else {
    $stmt = $mysqli->prepare("INSERT INTO likes (user_id, recipe_id) VALUES (?, ?)");
}
$stmt->bind_param("ii", $user_id, $recipe_id);
$stmt->execute();
$stmt->close();

// ===== Υπολογισμός νέου αριθμού likes =====
$stmt = $mysqli->prepare("SELECT COUNT(*) as total FROM likes WHERE recipe_id = ?");
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();
$like_count = $result->fetch_assoc()['total'];
$stmt->close();
$mysqli->close();

// ===== Δημιουργία μηνύματος εμφάνισης =====
if ($has_liked) {
    $message = $like_count === 0 ? "" : "Αρέσει σε " . $like_count . " χρήστες";
    $liked = false;
} else {
    $message = $like_count === 1 
        ? "Αρέσει σε εσάς" 
        : "Αρέσει σε εσάς και σε άλλους " . ($like_count - 1);
    $liked = true;
}

// ===== Τελική απάντηση AJAX =====
echo json_encode([
    'success' => true,
    'likes' => $like_count,
    'liked' => $liked,
    'message' => $message
]);