<?php
// ===== Έναρξη συνεδρίας για να διατηρηθεί η ταυτότητα του χρήστη =====
session_start();

// ===== Αν δεν είναι συνδεδεμένος χρήστης, διακόπτουμε την εκτέλεση =====
if (!isset($_SESSION['user_id'])) {
    exit();
}

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php");
$mysqli = $conn;

// ===== Λήψη του user_id από τη συνεδρία και ανάκτηση των συνταγών του =====
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT id, title, description, image_path, created_at FROM recipes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Οι Συνταγές μου</title>
  <link rel="stylesheet" href="../css/style.css">

  <!-- ===== Εισαγωγή SweetAlert2 για επιβεβαίωση διαγραφής ===== -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- ===== Flash μήνυμα επιτυχίας αν υπάρχει ===== -->
<?php if (isset($_GET['success'])): ?>
    <div class="flash-message">
        ✅ <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
    <script>
        setTimeout(() => {
            const msg = document.querySelector('.flash-message');
            if (msg) msg.style.display = 'none';
        }, 4000);
    </script>
<?php endif; ?>

<!-- ===== Εισαγωγή navbar ===== -->
<?php include("navbar.php"); ?>

<div class="page-container">
  <h2 class="section-title">Οι Συνταγές μου</h2>

  <!-- ===== Αν δεν υπάρχουν συνταγές ===== -->
  <?php if ($result->num_rows === 0): ?>
    <div class="empty-cart-message">
      Δεν έχετε ανεβάσει ακόμη καμία συνταγή!
    </div>

  <!-- ===== Αν υπάρχουν, εμφάνιση συνταγών ===== -->
  <?php else: ?>
    <div class="recipe-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="recipe-card">
          <!-- ===== Εικόνα συνταγής ===== -->
          <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Εικόνα Συνταγής" class="recipe-thumb">
          
          <!-- ===== Τίτλος, ημερομηνία και σύντομη περιγραφή ===== -->
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <small>Ανέβηκε: <?php echo $row['created_at']; ?></small>
          <p><?php echo nl2br(htmlspecialchars(substr($row['description'], 0, 100))) . "..."; ?></p>

          <!-- ===== Κουμπιά προβολής και επεξεργασίας ===== -->
          <a href="view_recipe.php?id=<?php echo $row['id']; ?>" class="btn-view">Προβολή</a>
          <a href="edit_recipe.php?id=<?php echo $row['id']; ?>" class="btn-edit">Επεξεργασία</a>

          <!-- ===== Φόρμα διαγραφής με SweetAlert επιβεβαίωση ===== -->
          <form method="POST" action="delete_recipe.php" class="delete-form">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <button type="button" class="btn-delete" onclick="confirmDelete(this.form)">🗑️ Διαγραφή</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<!-- ===== Footer ===== -->
<?php include("footer.php"); ?>

<!-- ===== JavaScript για επιβεβαίωση διαγραφής με SweetAlert2 ===== -->
<script>
function confirmDelete(form) {
  Swal.fire({
    title: 'Είστε σίγουρος;',
    text: "Η συνταγή θα διαγραφεί οριστικά.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e53935',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ναι, διαγραφή',
    cancelButtonText: 'Άκυρο'
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit(); // Υποβολή της φόρμας αν επιβεβαιώσει
    }
  });
}
</script>

</body>
</html>

<?php
// ===== Κλείσιμο πόρων βάσης δεδομένων =====
$stmt->close();
$mysqli->close();
?>