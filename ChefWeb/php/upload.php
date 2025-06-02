<?php
// ======= Έναρξη συνεδρίας =======
session_start();

// ======= Αν δεν είναι συνδεδεμένος, ανακατεύθυνση για εγγραφή =======
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php?message=Πρέπει να εγγραφείτε για να ανεβάσετε συνταγή.");
    exit();
}

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;

// ======= Έλεγχος επιτυχούς σύνδεσης =======
if ($mysqli->connect_error) {
    die("Σφάλμα σύνδεσης: " . $mysqli->connect_error);
}

// ======= Αρχικοποίηση μεταβλητών για εμφάνιση μηνυμάτων =======
$success = false;
$error = "";

// ======= Επεξεργασία φόρμας όταν γίνει POST =======
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ======= Ανάκτηση δεδομένων από τη φόρμα =======
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];

    // ======= Έλεγχος αν υποβλήθηκε εικόνα και δεν υπήρξε σφάλμα =======
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        // ======= Ορισμός μονοπατιών αποθήκευσης εικόνας =======
        $upload_folder = "../media/uploads/";  // φάκελος στο server
        $img_name = uniqid() . "_" . basename($_FILES['image']['name']); // μοναδικό όνομα
        $img_tmp = $_FILES['image']['tmp_name']; // προσωρινό path
        $img_path_fs = $upload_folder . $img_name; // πλήρες path στο filesystem

        // ======= Το path που θα αποθηκευτεί στη βάση για εμφάνιση στο site =======
        $img_path_db = "media/uploads/" . $img_name;

        // ======= Αποθήκευση εικόνας στον φάκελο =======
        if (move_uploaded_file($img_tmp, $img_path_fs)) {

            // ======= Προετοιμασία και εκτέλεση εισαγωγής της συνταγής στη βάση =======
            $stmt = $mysqli->prepare("INSERT INTO recipes (user_id, title, description, image_path, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $title, $description, $img_path_db, $category);

            if ($stmt->execute()) {
                $success = true; // επιτυχής καταχώρηση
            } else {
                $error = "Σφάλμα κατά την αποθήκευση: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error = "Σφάλμα κατά την αποθήκευση της εικόνας.";
        }
    } else {
        $error = "Πρέπει να ανεβάσετε μια εικόνα.";
    }
}

// ======= Κλείσιμο σύνδεσης με βάση =======
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Ανέβασε Συνταγή</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- ======= Πλοήγηση ======= -->
<?php include("navbar.php"); ?>

<!-- ======= Κοντέινερ φόρμας ανάρτησης ======= -->
<div class="form-container">
    <h2 class="form-title">Ανέβασε Νέα Συνταγή</h2>

    <!-- ======= Εμφάνιση μηνύματος επιτυχίας ή σφάλματος ======= -->
    <?php if ($success): ?>
        <p class="success-message">Η συνταγή καταχωρήθηκε με επιτυχία!</p>
    <?php elseif (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- ======= Φόρμα ανάρτησης συνταγής ======= -->
    <form method="POST" action="upload.php" enctype="multipart/form-data">
        <label>Τίτλος Συνταγής:</label>
        <input type="text" name="title" required>

        <label>Περιγραφή:</label>
        <textarea name="description" rows="5" required></textarea>

        <label>Είδος Κουζίνας:</label>
        <select name="category" required>
            <option value="Μεσογειακή">Μεσογειακή</option>
            <option value="Κινέζικη">Κινέζικη</option>
            <option value="Μεξικάνικη">Μεξικάνικη</option>
        </select>

        <label>Φωτογραφία:</label>
        <input type="file" name="image" accept="image/*" required>

        <input type="submit" value="Ανέβασε">
    </form>
</div>

<!-- ======= Μήνυμα flash που εξαφανίζεται αυτόματα ======= -->
<?php if ($success): ?>
  <div class="flash-message">✅ Η συνταγή σου ανέβηκε με επιτυχία!</div>
  <script>
    setTimeout(() => {
      const msg = document.querySelector('.flash-message');
      if (msg) msg.style.display = 'none';
    }, 4000); // Εξαφανίζεται μετά από 4 δευτερόλεπτα
  </script>
<?php endif; ?>

<!-- ======= Footer ======= -->
<?php include("footer.php"); ?>
</body>
</html>