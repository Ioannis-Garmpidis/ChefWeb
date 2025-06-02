<?php
session_start();

// ====== ΠΡΟΣΤΑΣΙΑ: Μόνο για συνδεδεμένους χρήστες ======
if (!isset($_SESSION['user_id'])) {
    exit();
}

// ====== ΕΛΕΓΧΟΣ: Υποχρεωτικό ID από POST και έλεγχος αριθμητικότητας ======
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: my_recipes.php?error=Μη έγκυρη συνταγή.");
    exit();
}

$recipe_id = intval($_POST['id']);
$user_id = $_SESSION['user_id'];

// ====== Σύνδεση με τη βάση δεδομένων ======
include("db_connection.php");
$mysqli = $conn;

// ====== ΕΛΕΓΧΟΣ: Ανήκει η συνταγή στον χρήστη; ======
$stmt = $mysqli->prepare("SELECT image_path FROM recipes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Δεν βρέθηκε ή δεν ανήκει στον χρήστη
    header("Location: my_recipes.php?error=Δεν επιτρέπεται η διαγραφή.");
    exit();
}

$row = $result->fetch_assoc();
$image_path = $row['image_path']; 
$stmt->close();

// ====== ΚΑΘΑΡΙΣΜΟΣ: Διαγραφή από likes, favorites, comments ======
$mysqli->query("DELETE FROM likes WHERE recipe_id = $recipe_id");
$mysqli->query("DELETE FROM favorites WHERE recipe_id = $recipe_id");
$mysqli->query("DELETE FROM comments WHERE recipe_id = $recipe_id");

// ====== ΔΙΑΓΡΑΦΗ ΣΥΝΤΑΓΗΣ ======
$stmt_del = $mysqli->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
$stmt_del->bind_param("ii", $recipe_id, $user_id);
$stmt_del->execute();
$stmt_del->close();

// ====== ΔΙΑΓΡΑΦΗ ΕΙΚΟΝΑΣ (από δίσκο) ======
$absolute_path = "../" . $image_path;  // πλήρης σχετική διαδρομή

if (file_exists($absolute_path)) {
    unlink($absolute_path);  // διαγραφή αρχείου
}

// ====== Ολοκλήρωση με επιβεβαίωση ======
header("Location: my_recipes.php?message=Η συνταγή διαγράφηκε με επιτυχία.");
exit();
?>