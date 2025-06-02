<?php
// ======= Έναρξη συνεδρίας =======
session_start();

// ======= Καθαρισμός όλων των μεταβλητών συνεδρίας =======
$_SESSION = [];
session_unset();
session_destroy();

// ======= Διαγραφή του cookie του session από τον browser (προληπτικά) =======
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// ======= Επιστροφή στην αρχική σελίδα με ένδειξη αποσύνδεσης =======
header("Location: index.php?loggedout=1");
exit();
?>