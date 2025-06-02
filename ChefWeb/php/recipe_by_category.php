<?php
// ===== Έναρξη του session για να γνωρίζουμε τον χρήστη (αν είναι συνδεδεμένος) =====
session_start();

// ===== Έλεγχος αν έχει δοθεί κατηγορία στο URL μέσω GET =====
if (!isset($_GET['category']) || empty(trim($_GET['category']))) {
    // Αν όχι, επιστρέφει στην αρχική
    header("Location: index.php");
    exit();
}

// ===== Καθαρισμός και αποκωδικοποίηση της παραμέτρου κατηγορίας από το URL =====
$category = urldecode(trim($_GET['category']));

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;

// ===== Έλεγχος αν η σύνδεση απέτυχε =====
if ($mysqli->connect_error) {
    die("Σφάλμα σύνδεσης: " . $mysqli->connect_error);
}

// ===== Ετοιμασία SQL ερωτήματος για λήψη συνταγών της συγκεκριμένης κατηγορίας =====
$stmt = $mysqli->prepare("
    SELECT r.id, r.title, r.description, r.image_path, r.created_at, u.firstname, u.lastname
    FROM recipes r
    JOIN users u ON r.user_id = u.id
    WHERE r.category = ?
    ORDER BY r.created_at DESC
");

// ===== Δέσμευση της κατηγορίας στο ερώτημα και εκτέλεση =====
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- ===== HTML αρχή ===== -->
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Συνταγές: <?php echo htmlspecialchars($category); ?></title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- ===== Εισαγωγή της μπάρας πλοήγησης ===== -->
<?php include("navbar.php"); ?>

<!-- ===== Κεντρικό container για εμφάνιση των συνταγών της κατηγορίας ===== -->
<div class="page-container">
  <h2 class="section-title">Συνταγές για: <?php echo htmlspecialchars($category); ?></h2>

  <?php if ($result->num_rows === 0): ?>
    <!-- Μήνυμα αν δεν υπάρχουν καταχωρημένες συνταγές για την κατηγορία -->
    <p>Δεν υπάρχουν ακόμη συνταγές σε αυτή την κατηγορία.</p>
  <?php else: ?>
    <!-- Αν υπάρχουν συνταγές, τις εμφανίζει σε πλέγμα -->
    <div class="recipe-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="recipe-card">
          <!-- Εικόνα συνταγής -->
        <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Εικόνα Συνταγής" class="recipe-thumb">
          <!-- Τίτλος συνταγής -->
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <!-- Όνομα δημιουργού -->
          <small>Από: <?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?></small><br>
          <!-- Ημερομηνία ανάρτησης -->
          <small>Ημερομηνία: <?php echo $row['created_at']; ?></small>
          <!-- Περιγραφή συνταγής (περιληπτικά) -->
          <p><?php echo nl2br(htmlspecialchars(substr($row['description'], 0, 100))) . "..."; ?></p>
          <!-- Κουμπί για πλήρη προβολή της συνταγής -->
          <a href="view_recipe.php?id=<?php echo $row['id']; ?>" class="btn-view">Προβολή</a>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<!-- ===== Εισαγωγή του footer ===== -->
<?php include("footer.php"); ?>

</body>
</html>

<?php
// ===== Κλείσιμο της σύνδεσης και των statements =====
$stmt->close();
$mysqli->close();
?>