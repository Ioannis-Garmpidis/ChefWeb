<?php
// ===== Εμφάνιση όλων των σφαλμάτων =====
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;

// ===== Έλεγχος επιτυχούς σύνδεσης =====
if ($mysqli->connect_error) {
    die('Σφάλμα σύνδεσης: ' . $mysqli->connect_error);
}

// ===== Αρχικοποίηση Μεταβλητών για Εμφάνιση Τιμών στη Φόρμα =====
$firstname = '';
$lastname = '';
$email = '';
$phone = '';
$address = '';
$success = false;  // Για επιτυχημένη εγγραφή
$error = '';       // Για μηνύματα σφάλματος

// ===== Επεξεργασία Φόρμας όταν γίνει Υποβολή =====
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ===== Λήψη και Καθαρισμός Στοιχείων Φόρμας =====
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];           // Αρχικός κωδικός
    $password_confirm = $_POST['password_confirm']; // Επιβεβαίωση κωδικού

    // ===== Βασικοί Έλεγχοι =====

    // Έλεγχος αν το email είναι σωστά γραμμένο
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Μη έγκυρη διεύθυνση email.";
    }
    // Έλεγχος ισχυρότητας κωδικού (γράμματα + αριθμοί, τουλάχιστον 6 χαρακτήρες)
    elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password_raw)) {
        $error = "Ο κωδικός πρέπει να έχει τουλάχιστον 6 χαρακτήρες και να περιέχει γράμματα και αριθμούς.";
    }
    // Έλεγχος ότι οι δύο κωδικοί ταιριάζουν
    elseif ($password_raw !== $password_confirm) {
        $error = "Οι κωδικοί πρόσβασης δεν ταιριάζουν.";
    }
    else {
        // ===== Έλεγχος για Υπάρχοντα Χρήστη με Ίδια Στοιχεία =====
        $stmt_check = $mysqli->prepare("SELECT id FROM users WHERE firstname = ? AND lastname = ? AND email = ?");
        $stmt_check->bind_param("sss", $firstname, $lastname, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Ο χρήστης με αυτά τα στοιχεία υπάρχει ήδη.";
        }
        $stmt_check->close();

        // ===== Εγγραφή Χρήστη Αν Δεν Υπάρχει =====
        if (!$error) {
            // Κρυπτογράφηση κωδικού πριν την αποθήκευση
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            // Προετοιμασία και εκτέλεση της εισαγωγής
            $stmt = $mysqli->prepare("INSERT INTO users (firstname, lastname, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

            if ($stmt->execute()) {
                // ===== Αν πετύχει η εγγραφή, αποθήκευση στοιχείων χρήστη στο session =====
                $_SESSION['user_id'] = $mysqli->insert_id;
                $_SESSION['first_name'] = $firstname;
                $_SESSION['last_name'] = $lastname;
                $_SESSION['user'] = $firstname . " " . $lastname;
                $success = true; // Επιτυχής εγγραφή
            } else {
                $error = "Σφάλμα κατά την εγγραφή: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

// ===== Κλείσιμο της Σύνδεσης με τη Βάση =====
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Εγγραφή</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include("navbar.php"); ?>

<div class="form-container">
<?php if ($success): ?>
    <!-- Μήνυμα Επιτυχίας -->
    <h2 class="success-message">Η εγγραφή ολοκληρώθηκε επιτυχώς!</h2>
    <form method="post" action="index.php" class="continue-form">
        <input type="submit" value="Συνέχεια">
    </form>
<?php else: ?>
    
    <!-- Φόρμα Εγγραφής -->
    <h2 class="form-title">Φόρμα Εγγραφής</h2>

    <!-- Εμφάνιση Μηνύματος Σφάλματος αν Υπάρχει -->
    <?php if (!empty($error)): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="signup.php">
      <label>Όνομα:</label>
      <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required pattern="[A-Za-zΑ-Ωα-ωΆ-Ώά-ώ\s]+" title="Μόνο ελληνικά ή λατινικά γράμματα">
      <label>Επώνυμο:</label>
      <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required pattern="[A-Za-zΑ-Ωα-ωΆ-Ώά-ώ\s]+" title="Μόνο ελληνικά ή λατινικά γράμματα">
      <label>Email:</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required title="Σωστή διεύθυνση email">
      <label>Κωδικός:</label>
      <input type="password" name="password" required minlength="6" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$" title="Τουλάχιστον 6 χαρακτήρες, γράμματα και αριθμοί">
      <label>Επαλήθευση Κωδικού:</label>
      <input type="password" name="password_confirm" required minlength="6" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$" title="Πρέπει να πληκτρολογήσετε ξανά τον κωδικό σας">
      <input type="submit" value="Εγγραφή">
    </form>
<?php endif; ?>
</div>

<?php include("footer.php"); ?>

</body>
</html>