<?php
// ===== Ξεκινάμε το Session =====
session_start();

// ===== Αποθήκευση στοιχείων χρήστη αν υπάρχει σύνδεση =====
$user = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : null;

// ===== Λογική για εμφάνιση Popups =====
$popupSeen = isset($_SESSION['popup_shown']); // Έχει δει ήδη popup σύνδεσης;
$showGoodbye = isset($_GET['loggedout']) && !$user; // Έγινε αποσύνδεση χρήστη;
$showLoginPopup = !$user && !$popupSeen && !$showGoodbye; // Δεν είναι συνδεδεμένος και δεν έχει δει popup

if ($showLoginPopup) {
    $_SESSION['popup_shown'] = true; // Σημειώνουμε ότι έχει δει το popup
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Chef Web - Αρχική</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">

    <!-- ===== Scripts εμφάνισης Popups ===== -->
    <?php if ($showLoginPopup): ?>
    <script>
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            const popup = document.getElementById('popup-login');
            if (popup) popup.style.display = 'flex';
        }, 5000); // Εμφάνιση του popup μετά από 5 δευτερόλεπτα
    });
    </script>
    <?php endif; ?>

    <?php if ($showGoodbye): ?>
    <script>
    window.addEventListener('DOMContentLoaded', () => {
        const popup = document.getElementById('popup-goodbye');
        if (popup) popup.style.display = 'flex';
    });
    </script>
    <?php endif; ?>

    <!-- ===== Συναρτήσεις Κλεισίματος Popups ===== -->
    <script>
    function closePopup(id) {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }

    function closeGoodbyeAndShowLogin() {
        closePopup('popup-goodbye');
        setTimeout(() => {
            const loginPopup = document.getElementById('popup-login');
            if (loginPopup) loginPopup.style.display = 'flex';
        }, 5000);
    }
    </script>
</head>

<body>

<!-- ===== Navbar Εισαγωγή ===== -->
<?php include("navbar.php"); ?>

<!-- ===== Μήνυμα Καλωσορίσματος ===== -->
<?php if ($user): ?>
    <h3 class="welcome-message">
        Καλωσήρθες, <?php echo htmlspecialchars($user); ?>!
    </h3>
<?php endif; ?>

<!-- ===== Κατηγορίες Κουζινών ===== -->
<section class="categories">
  <div class="row1">

    <div class="category1">
      <a href="recipe_by_category.php?category=Μεσογειακή">
        <img class="cuisine" src="../media/categories/mediterranean.png" alt="Μεσογειακή Κουζίνα">
      </a>
      <p>Μεσογειακή Κουζίνα</p>
    </div>

    <div class="category2">
      <a href="recipe_by_category.php?category=Κινέζικη">
        <img class="cuisine" src="../media/categories/chinese.png" alt="Κινέζικη Κουζίνα">
      </a>
      <p>Κινέζικη Κουζίνα</p>
    </div>

    <div class="category3">
      <a href="recipe_by_category.php?category=Μεξικάνικη">
        <img class="cuisine" src="../media/categories/mexican.png" alt="Μεξικάνικη Κουζίνα">
      </a>
      <p>Μεξικάνικη Κουζίνα</p>
    </div>
  </div>
</section>

<!-- ===== Footer ===== -->
<?php include("footer.php"); ?>

<!-- ===== Popup Σύνδεσης ===== -->
<div id="popup-login" class="popup-container" style="display: none;">
    <div class="popup-box">
        <span class="popup-close" onclick="closePopup('popup-login')">✕</span>
        <h3>Καλώς ήρθατε! Επιλέξτε:</h3>
        <a href="login.php" class="popup-button">Σύνδεση</a>
        <a href="signup.php" class="popup-button">Εγγραφή</a>
        <a href="index.php" class="popup-button">Συνέχεια ως επισκέπτης</a>
    </div>
</div>

<!-- ===== Popup Αποχαιρετισμού ===== -->
<?php if ($showGoodbye): ?>
<div id="popup-goodbye" class="popup-container" style="display: none;">
    <div class="popup-box">
        <span class="popup-close" onclick="closePopup('popup-goodbye')">✕</span>
        <h3>Ευχαριστούμε για την επίσκεψη!</h3>
        <a class="popup-button" onclick="closeGoodbyeAndShowLogin()">Κλείσιμο</a>
    </div>
</div>
<?php endif; ?>

</body>
</html>