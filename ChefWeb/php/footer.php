<!-- ======= footer ======= -->

<footer>

  <!-- ======= Δημοφιλείς Κατηγορίες ======= -->
  <div class="popular">
 <p class="footer-heading">Δημοφιλείς Κατηγορίες</p>
  <p><a href="recipe_by_category.php?category=Μεσογειακή">Μεσογειακή Κουζίνα</a></p>
  <p><a href="recipe_by_category.php?category=Κινέζικη">Κινέζικη Κουζίνα</a></p>
  <p><a href="recipe_by_category.php?category=Μεξικάνικη">Μεξικάνικη Κουζίνα</a></p>
</div>

  <!-- ======= Footer Λινκ: Δραστηριότητα======= -->
  <div class="activity">
 <p class="footer-heading">Δραστηριότητα</p>
<!-- ======= Footer Λινκ: Ανέβασε Συνταγή ======= -->
<?php if (!isset($_SESSION['user_id'])): ?>
  <p><a href="#" onclick="openAuthPopup()">Ανέβασε Συνταγή</a></p>
<?php else: ?>
  <p><a href="upload.php">Ανέβασε Συνταγή</a></p>
<?php endif; ?>

<!-- ======= Footer Λινκ: Οι Συνταγές μου ======= -->
<?php if (!isset($_SESSION['user_id'])): ?>
  <p><a href="#" onclick="openAuthPopup()">Οι Συνταγές μου</a></p>
<?php else: ?>
  <p><a href="my_recipes.php">Οι Συνταγές μου</a></p>
<?php endif; ?>

<!-- ======= Footer Λινκ: Αγαπημένα ======= -->
<?php if (!isset($_SESSION['user_id'])): ?>
  <p><a href="#" onclick="openAuthPopup()">Αγαπημένα</a></p>
<?php else: ?>
  <p><a href="favorite.php">Αγαπημένα</a></p>
<?php endif; ?>
</div>

  <!-- ======= Social Media ======= -->
  <div class="social-media">
    <p class="footer-heading">Ακολουθήστε μας...</p>
    <img id="social" src="../media/imgSocial.png" alt="Social media">
    
    <!-- ======= Πνευματικά δικαιώματα ======= -->
    <small class="copyright">&copy; 2025 Chef Web</small>
  </div>

</footer>