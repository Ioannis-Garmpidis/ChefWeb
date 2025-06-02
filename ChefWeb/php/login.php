<?php
// ===== Έναρξη του Session =====
session_start();

// ===== Σύνδεση με τη βάση δεδομένων =====
include("db_connection.php"); 
$mysqli = $conn;

// ===== Έλεγχος Σύνδεσης =====
if ($mysqli->connect_error) {
    die("Σφάλμα σύνδεσης: " . $mysqli->connect_error);
}

// ===== Αρχικοποίηση μεταβλητής για σφάλματα =====
$error = '';

// ===== Επεξεργασία της Φόρμας Σύνδεσης όταν γίνει Υποβολή =====
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ===== Λήψη και Καθαρισμός Στοιχείων από την Φόρμα =====
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // ===== Προετοιμασία Αναζήτησης Χρήστη στη Βάση =====
    $stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // Δένουμε το email στην ερώτηση
    $stmt->execute();
    $stmt->store_result();

    // ===== Έλεγχος Αν Βρέθηκε Χρήστης =====
    if ($stmt->num_rows == 1) {
        // ===== Δέσμευση αποτελεσμάτων =====
        $stmt->bind_result($id, $firstname, $lastname, $hashed_password);
        $stmt->fetch();

        // ===== Έλεγχος Αν ο Κωδικός Ταιριάζει =====
        if (password_verify($password, $hashed_password)) {
            // ===== Επιτυχής Σύνδεση, Αποθήκευση στοιχείων στο Session =====
            $_SESSION['user_id'] = $id;
            $_SESSION['first_name'] = $firstname;
            $_SESSION['last_name'] = $lastname;
            $_SESSION['user'] = $firstname . ' ' . $lastname;

            // ===== Ανακατεύθυνση στην Αρχική Σελίδα =====
            header("Location: index.php");
            exit();
        } else {
            // ===== Αν αποτύχει ο κωδικός =====
            $error = "Λάθος κωδικός.";
        }
    } else {
        // ===== Αν δεν βρέθηκε το email =====
        $error = "Δεν βρέθηκε λογαριασμός με αυτό το email.";
    }

    // ===== Κλείσιμο Ερωτήματος =====
    $stmt->close();
}

// ===== Κλείσιμο Σύνδεσης με Βάση =====
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Σύνδεση</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- ===== Navbar ===== -->
<?php include("navbar.php"); ?>

<!-- ===== Φόρμα Σύνδεσης ===== -->
<div class="form-container">
    <h2>Σύνδεση Χρήστη</h2>

    <!-- ===== Εμφάνιση Μηνύματος Σφάλματος αν Υπάρχει ===== -->
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- ===== Φόρμα Εισαγωγής Email και Κωδικού ===== -->
    <form method="POST" action="login.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Κωδικός:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Σύνδεση">
    </form>
</div>

<!-- ===== Footer ===== -->
<?php include("footer.php"); ?>

</body>
</html>