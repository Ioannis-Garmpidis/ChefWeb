-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: localhost
-- Χρόνος δημιουργίας: 02 Ιουν 2025 στις 16:23:10
-- Έκδοση διακομιστή: 10.4.28-MariaDB
-- Έκδοση PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `ChefWeb`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `recipe_id`, `comment`, `created_at`) VALUES
(1, 4, 1, 'Πεντανόστιμο!', '2025-05-12 20:07:29'),
(2, 3, 1, 'Ισχύει!!!', '2025-05-12 20:10:05');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `recipe_id`) VALUES
(3, 1, 1),
(4, 4, 1);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `recipe_id`) VALUES
(1, 1, 1),
(3, 3, 1),
(2, 4, 1);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `recipes`
--

INSERT INTO `recipes` (`id`, `user_id`, `title`, `description`, `image_path`, `category`, `created_at`) VALUES
(1, 1, 'Σπιτικός Μουσακάς με αγνά υλικά!', 'Υλικά:\r\n\r\n• 2 μελιτζάνες κομμένες σε φέτες\r\n• 500 γρ. κιμά μοσχαρίσιο\r\n• 1 κρεμμύδι ψιλοκομμένο\r\n• 2 ντομάτες τριμμένες ή 1/2 φλ. σάλτσα\r\n• 3 κ.σ. ελαιόλαδο\r\n• Αλάτι, πιπέρι, κανέλα (προαιρετικά)\r\n\r\nΓια τη μπεσαμέλ:\r\n\r\n• 2 κ.σ. βούτυρο\r\n• 2 κ.σ. αλεύρι\r\n• 2 φλ. γάλα\r\n• 1 αυγό\r\n• Αλάτι, μοσχοκάρυδο\r\n\r\nΟδηγίες:\r\n\r\n1. Ψήσε τις μελιτζάνες στο φούρνο ή τη σχάρα με λίγο λάδι.\r\n2. Σοτάρισε το κρεμμύδι με τον κιμά, πρόσθεσε τις ντομάτες, αλάτι και πιπέρι. Άφησέ τον να σιγοβράσει για 20 λεπτά.\r\n3. Για τη μπεσαμέλ: Ζέστανε το βούτυρο, ρίξε το αλεύρι και ανακάτεψε. Πρόσθεσε το γάλα και στο τέλος το αυγό με λίγο μοσχοκάρυδο.\r\n4. Στρώσε σε ταψί μελιτζάνες, κιμά και επανέλαβε. Από πάνω ρίξε τη μπεσαμέλ.\r\n5. Ψήσε στους 180°C για 35–40 λεπτά μέχρι να ροδίσει.\r\n\r\nΣερβίρεται ζεστό ή σε θερμοκρασία δωματίου.', 'media/uploads/6821cedf485a1_Μουσακάς.jpg', 'Μεσογειακή', '2025-05-12 13:35:11');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `created_at`) VALUES
(1, 'Ioannis', 'Garmpidis', 'ioannis@gmail.com', '$2y$10$rwMgZHKxrjIgI1hSsqhBL.vEKpveSdxa2hIwVl7nUNDJT6CMoD2ru', '2025-05-01 23:42:57'),
(2, 'Nektarios', 'Kotridis', 'kotridis@gmail.com', '$2y$10$//k/gWAK2DcdJzKdqSbIpuNtG.uWlRsou1b0dDARJKpZnSzw6nexy', '2025-05-03 11:16:05'),
(3, 'Mahi', 'Adamou', 'mahi@gmail.com', '$2y$10$.VDR.4d0oyrxFU0gCe6NJ.A4C.CxP2WiJF1SXRn/7a5MU8LP6fdOW', '2025-05-06 21:41:00'),
(4, 'Michael', 'Jordan', 'thegoat@gmail.com', '$2y$10$nJ6l5xL/b18H2htYf0x8GOBiMIrQ5.SmlTp9GT.WECZUEwVH5swvC', '2025-05-12 12:55:41');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Ευρετήρια για πίνακα `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Ευρετήρια για πίνακα `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`recipe_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Ευρετήρια για πίνακα `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT για πίνακα `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT για πίνακα `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT για πίνακα `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
