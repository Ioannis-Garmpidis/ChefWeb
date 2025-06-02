<?php
// ===== Πληροφορίες Σύνδεσης Βάσης Δεδομένων =====
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "ChefWeb";

// ===== Δημιουργία Σύνδεσης =====
$conn = new mysqli($servername, $username, $password, $dbname);

// ===== Έλεγχος Σύνδεσης =====
if ($conn->connect_error) {
    die("Σφάλμα σύνδεσης: " . htmlspecialchars($conn->connect_error));
}
?>