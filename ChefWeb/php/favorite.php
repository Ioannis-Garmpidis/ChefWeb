<?php
// ===== Έναρξη συνεδρίας =====
session_start();

// ===== Έλεγχος αν ο χρήστης είναι συνδεδεμένος =====
if (!isset($_SESSION['user_id'])) {
    exit();
}

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;


// ===== Ανάκτηση του user_id από το session =====
$user_id = $_SESSION['user_id'];

// ===== SQL για ανάκτηση όλων των αγαπημένων συνταγών του χρήστη =====
$query = "
SELECT r.id, r.title, r.description, r.image_path, r.created_at, u.firstname, u.lastname
FROM favorites f
JOIN recipes r ON f.recipe_id = r.id
JOIN users u ON r.user_id = u.id
WHERE f.user_id = ?
ORDER BY f.id DESC
";

// ===== Προετοιμασία και εκτέλεση του ερωτήματος =====
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Αγαπημένες Συνταγές</title>
  <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include("navbar.php"); ?>

<div class="page-container">
  <h2 class="section-title">Αγαπημένες Συνταγές</h2>

  <?php if ($result->num_rows === 0): ?>
    <!-- Αν δεν υπάρχουν αγαπημένες -->
    <div class="empty-cart-message">
      Δεν υπάρχουν αγαπημένες συνταγές ακόμα!
    </div>
  <?php else: ?>
    <!-- Αν υπάρχουν, εμφάνιση σε grid -->
    <div class="recipe-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <!-- Κάρτα συνταγής -->
        <div class="recipe-card" data-id="<?php echo $row['id']; ?>">
          <!-- Εικόνα συνταγής -->
          <img src="/ChefWeb/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Εικόνα Συνταγής" class="recipe-thumb">
          <!-- Τίτλος -->
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <!-- Όνομα δημιουργού -->
          <small>Ανέβηκε από: <?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?></small><br>
          <!-- Ημερομηνία δημιουργίας -->
          <small>Ημερομηνία: <?php echo $row['created_at']; ?></small>
          <!-- Περιγραφή περιορισμένη στους 100 χαρακτήρες -->
          <p><?php echo nl2br(htmlspecialchars(substr($row['description'], 0, 100))) . "..."; ?></p>

          <!-- Κουμπί Προβολής Συνταγής -->
          <a href="view_recipe.php?id=<?php echo $row['id']; ?>" class="btn-view">Προβολή</a>

          <!-- Κουμπί Αφαίρεσης από Αγαπημένα -->
          <button class="remove-btn saved" data-id="<?php echo $row['id']; ?>">🗑️ Αφαίρεση από Αγαπημένα</button>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<?php include("footer.php"); ?>

<!-- ===== JavaScript για αφαίρεση αγαπημένων με AJAX ===== -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const removeButtons = document.querySelectorAll(".remove-btn");

  removeButtons.forEach(function (btn) {
    btn.addEventListener("click", function () {
      const recipeId = this.dataset.id;
      const recipeCard = this.closest(".recipe-card");

      // Αίτημα AJAX για αφαίρεση από τα αγαπημένα
      fetch("save_favorite_ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `recipe_id=${recipeId}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success && recipeCard) {
          // Αφαίρεση της κάρτας από το DOM
          recipeCard.remove();
        } else {
          alert("Αποτυχία διαγραφής από αγαπημένα.");
        }
      });
    });
  });
});
</script>
</body>
</html>

<?php
// ===== Κλείσιμο πόρων =====
$stmt->close();
$mysqli->close();
?>