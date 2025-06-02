<?php
// ======= Έναρξη συνεδρίας αν δεν έχει ξεκινήσει ήδη =======
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======= Ορισμός μεταβλητής χρήστη (όνομα χρήστη) =======
$user = $_SESSION['first_name'] ?? null;

// ======= Εντοπισμός της τρέχουσας σελίδας για ενεργό link =======
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- ======= ΕΝΟΤΗΤΑ ΠΛΟΗΓΗΣΗΣ ======= -->
<section class="navbar">
  <div class="header">
    <h1>Μάγειρας Μαχαίρας</h1>
  </div>

  <div>
    <ul class="menu-list">

      <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- Σύνδεση / Εγγραφή αν δεν είναι συνδεδεμένος -->
        <li class="menu-list-item">
          <a href="#" class="menu-link" onclick="openAuthPopup()">Σύνδεση ή Εγγραφή</a>
        </li>
      <?php endif; ?>

      <!-- Σταθεροί σύνδεσμοι navbar -->
      <li class="menu-list-item">
        <a class="menu-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="/ChefWeb/php/index.php">Αρχική</a>
<!-- === Ανέβασε Συνταγή === -->
<li class="menu-list-item">
  <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Αν ο χρήστης είναι συνδεδεμένος, προχωρά στην upload page -->
    <a href="upload.php" class="menu-link">Ανέβασε Συνταγή</a>
  <?php else: ?>
    <!-- Αν ΔΕΝ είναι συνδεδεμένος, εμφανίζεται το popup σύνδεσης/εγγραφής -->
    <a href="#" class="menu-link" onclick="openAuthPopup()">Ανέβασε Συνταγή</a>
  <?php endif; ?>
</li>

<!-- === Συνταγές Μου === -->
<li class="menu-list-item">
  <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Αν είναι συνδεδεμένος, προχωρά στην προσωπική του σελίδα συνταγών -->
    <a href="my_recipes.php" class="menu-link">Συνταγές μου</a>
  <?php else: ?>
    <!-- Αν ΔΕΝ είναι συνδεδεμένος, εμφανίζεται το popup σύνδεσης/εγγραφής -->
    <a href="#" class="menu-link" onclick="openAuthPopup()">Συνταγές μου</a>
  <?php endif; ?>
</li>

<!-- === Αγαπημένα === -->
<li class="menu-list-item">
  <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Αν είναι συνδεδεμένος, οδηγείται στη σελίδα αγαπημένων -->
    <a href="/ChefWeb/php/favorite.php" class="menu-link <?php echo ($current_page == 'favorite.php') ? 'active' : ''; ?>">Αγαπημένα</a>
  <?php else: ?>
    <!-- Αν ΔΕΝ είναι συνδεδεμένος, εμφανίζεται το popup σύνδεσης/εγγραφής -->
    <a href="#" class="menu-link" onclick="openAuthPopup()">Αγαπημένα</a>
  <?php endif; ?>
</li>
      <?php if ($user): ?>
        <!-- Αποσύνδεση αν είναι συνδεδεμένος -->
        <li class="menu-list-item">
          <a class="menu-link" href="/ChefWeb/php/logout.php">Αποσύνδεση</a>
        </li>
      <?php endif; ?>

    </ul>
  </div>
</section>

<!-- ======= ΕΝΟΤΗΤΑ ΛΟΓΟΤΥΠΟΥ ΚΑΙ ΦΡΑΣΗΣ ======= -->
<section class="logo">
  <img id="logo-img" src="/ChefWeb/media/logo.png" alt="logo chef">
  <div class="phrase">
    <h2> Συνταγές Μαγειρικής από όλο τον κόσμο......!!!</h2>
  </div>
</section>

<!-- ======= Φράση  ======= -->
<p id="subtitle">Food Lovers...</p>

<!-- ======= POPUP για Σύνδεση ή Εγγραφή ======= -->
<div id="authPopup" class="popup-container" style="display: none;">
  <div class="popup-box">
    <h3>Θέλετε να συνδεθείτε ή να εγγραφείτε;</h3>
    <a href="/ChefWeb/php/login.php" class="popup-button">Σύνδεση</a>
    <a href="/ChefWeb/php/signup.php" class="popup-button">Εγγραφή</a>
    <div class="popup-close" onclick="closeAuthPopup()">✕</div>
  </div>
</div>

<!-- ======= JAVASCRIPT για διαχείριση popup ======= -->
<script>
function openAuthPopup() {
  document.getElementById('authPopup').style.display = 'flex';
}

function closeAuthPopup() {
  document.getElementById('authPopup').style.display = 'none';
}
</script>