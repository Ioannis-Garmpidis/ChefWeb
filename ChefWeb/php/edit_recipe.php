<?php
session_start();

// ===== Έλεγχος αν είναι συνδεδεμένος χρήστης =====
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=Πρέπει να είστε συνδεδεμένος.");
    exit();
}

// ===== Έλεγχος ότι υπάρχει έγκυρο ID συνταγής στη διεύθυνση =====
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: my_recipes.php");
    exit();
}

$recipe_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// ===== Σύνδεση με βάση δεδομένων =====
include("db_connection.php");
$mysqli = $conn;

// ===== Έλεγχος ιδιοκτησίας της συνταγής από τον χρήστη =====
$stmt = $mysqli->prepare("SELECT title, description, image_path, category FROM recipes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ===== Αν η συνταγή δεν ανήκει στον χρήστη ή δεν υπάρχει =====
if ($result->num_rows !== 1) {
    header("Location: my_recipes.php?error=Δεν επιτρέπεται η επεξεργασία.");
    exit();
}

// ===== Ανάκτηση στοιχείων συνταγής =====
$recipe = $result->fetch_assoc();
$error = '';
$success = false;

// ===== Επεξεργασία POST όταν υποβληθεί η φόρμα =====
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_title = trim($_POST['title']);
    $new_desc = trim($_POST['description']);
    $new_category = $_POST['category'];
    $new_image_path = $recipe['image_path']; 

    // ===== Ανέβασμα νέας εικόνας (αν επιλέχθηκε) =====
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['image']['tmp_name'];
        $img_name = basename($_FILES['image']['name']);
        $upload_path = "../uploads/" . uniqid() . "_" . $img_name;

        if (move_uploaded_file($img_tmp, $upload_path)) {
            // Διαγραφή παλιάς εικόνας αν υπάρχει
            if (file_exists($recipe['image_path'])) {
                unlink($recipe['image_path']);
            }
            $new_image_path = $upload_path;
        } else {
            $error = "Αποτυχία αποθήκευσης της νέας εικόνας.";
        }
    }

    // ===== Ενημέρωση συνταγής στη βάση δεδομένων =====
    if (empty($error)) {
        $stmt_update = $mysqli->prepare("UPDATE recipes SET title = ?, description = ?, category = ?, image_path = ? WHERE id = ? AND user_id = ?");
        $stmt_update->bind_param("ssssii", $new_title, $new_desc, $new_category, $new_image_path, $recipe_id, $user_id);

        if ($stmt_update->execute()) {
            $success = true;

            // Ενημέρωση τοπικών μεταβλητών για άμεση εμφάνιση
            $recipe['title'] = $new_title;
            $recipe['description'] = $new_desc;
            $recipe['category'] = $new_category;
            $recipe['image_path'] = $new_image_path;

            // ===== Ανακατεύθυνση πίσω στη σελίδα "Οι Συνταγές Μου" =====
            header("Location: my_recipes.php?success=Η συνταγή ενημερώθηκε.");
            exit();
        } else {
            $error = "Σφάλμα κατά την ενημέρωση: " . $stmt_update->error;
        }

        $stmt_update->close();
    }
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Επεξεργασία Συνταγής</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- ======= Εισαγωγή Navigation Bar ======= -->
<?php include("navbar.php"); ?>

<!-- ======= Κοντέινερ Φόρμας Επεξεργασίας ======= -->
<div class="form-container">
  <h2 class="form-title">Επεξεργασία Συνταγής</h2>

  <!-- ======= Εμφάνιση Μηνυμάτων Επιτυχίας ή Σφάλματος ======= -->
  <?php if ($success): ?>
    <p class="success-message">Η συνταγή ενημερώθηκε με επιτυχία!</p>
  <?php elseif (!empty($error)): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <!-- ======= Φόρμα Επεξεργασίας Συνταγής ======= -->
  <form method="POST" action="" enctype="multipart/form-data">
    <label>Τίτλος:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>

    <label>Περιγραφή:</label>
    <textarea name="description" rows="5" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>

    <label>Κατηγορία:</label>
    <select name="category" required>
      <option value="Μεσογειακή" <?php if ($recipe['category'] === "Μεσογειακή") echo 'selected'; ?>>Μεσογειακή</option>
      <option value="Κινέζικη" <?php if ($recipe['category'] === "Κινέζικη") echo 'selected'; ?>>Κινέζικη</option>
      <option value="Μεξικάνικη" <?php if ($recipe['category'] === "Μεξικάνικη") echo 'selected'; ?>>Μεξικάνικη</option>
    </select>

    <label>Τρέχουσα Εικόνα:</label>
    <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="Τρέχουσα Εικόνα" class="recipe-thumb">

    <label>Αλλαγή Εικόνας (προαιρετικό):</label>
    <input type="file" name="image" accept="image/*">

    <input type="submit" value="Αποθήκευση Αλλαγών">
  </form>
</div>

<!-- ======= Εισαγωγή Footer ======= -->
<?php include("footer.php"); ?>

</body>
</html>