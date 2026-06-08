-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 08, 2026 at 06:24 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zadanie_semestralne`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `artysci`
--

CREATE TABLE `artysci` (
  `id` int(11) NOT NULL,
  `imie` varchar(255) DEFAULT NULL,
  `nazwisko` varchar(255) DEFAULT NULL,
  `pseudonim` varchar(255) DEFAULT NULL,
  `kraj` varchar(255) DEFAULT NULL,
  `gatunek` varchar(255) DEFAULT NULL,
  `data_urodzenia` date DEFAULT NULL,
  `opis` text DEFAULT NULL,
  `zdjecie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `artysci`
--

INSERT INTO `artysci` (`id`, `imie`, `nazwisko`, `pseudonim`, `kraj`, `gatunek`, `data_urodzenia`, `opis`, `zdjecie`) VALUES
(1, 'Taymor Travon', 'McIntyre', 'Tay-K', 'Stany Zjednoczone', 'Hip-hop', '2000-06-16', '', 'artysta_6a25d54bc1fcf.jpg'),
(2, 'Daria', 'Zawiałow', '', 'Polska', 'Pop, Rock', '1992-08-18', '', 'artysta_6a25d6b15e922.jpg'),
(3, 'Billie', 'Eilish Pirate Baird O’Connell', 'Billie Eilish', 'Stany Zjednoczone', 'Pop', '2001-12-18', '', 'artysta_6a25d6d90458d.jpg'),
(4, 'Alexander', 'Ridha', 'Boys Noize', 'Niemcy', 'House, Techno', '1982-08-22', '', 'artysta_6a25dba768281.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `projekty`
--

CREATE TABLE `projekty` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) NOT NULL,
  `typ` enum('album','ep','soundtrack') NOT NULL,
  `rok` int(11) DEFAULT NULL,
  `id_artysty` int(11) DEFAULT NULL,
  `id_zespolu` int(11) DEFAULT NULL,
  `okladka` varchar(255) DEFAULT NULL,
  `ocena_aoty` decimal(5,2) DEFAULT NULL,
  `ocena_rym` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `projekty`
--

INSERT INTO `projekty` (`id`, `tytul`, `typ`, `rok`, `id_artysty`, `id_zespolu`, `okladka`, `ocena_aoty`, `ocena_rym`) VALUES
(1, '† (Cross)', 'album', 2007, NULL, 1, 'projekt_6a25d58eede16.jpg', 85.00, 3.72),
(2, 'Audio, Video, Disco.', 'album', 2011, NULL, 1, 'projekt_6a25d5bc29035.jpg', 69.00, 2.97),
(3, 'Woman', 'album', 2016, NULL, 1, 'projekt_6a25d5d470d1a.jpg', 67.00, 3.02),
(4, 'Hyperdrama', 'album', 2024, NULL, 1, 'projekt_6a25d5f3bdc62.jpg', 75.00, 3.36),
(5, '#SantanaWorld', 'album', 2017, 1, NULL, 'projekt_6a25d61e9a7ab.jpg', 73.00, 3.54),
(6, '#LIVINGLIKELARRY', 'ep', 2017, 1, NULL, 'projekt_6a25d638e4616.jpg', 80.00, 3.62),
(7, 'A kysz!', 'album', 2017, 2, NULL, 'projekt_6a25d7057349d.jpg', 73.00, 3.03),
(8, 'Helsinki', 'album', 2019, 2, NULL, 'projekt_6a25d71b88fe6.jpg', 79.00, 3.39),
(9, 'Wojny i Noce', 'album', 2021, 2, NULL, 'projekt_6a25d732b9f5f.jpg', 69.00, 3.20),
(10, 'Dziewczyna Pop', 'album', 2023, 2, NULL, 'projekt_6a25d747b0d08.webp', 72.00, 2.92),
(11, 'don\'t smile at me', 'ep', 2017, 3, NULL, 'projekt_6a25d76e96b04.jpg', 72.00, 3.18),
(12, 'WHEN WE ALL FALL ASLEEP, WHERE DO WE GO?', 'album', 2019, 3, NULL, 'projekt_6a25d78c4c866.png', 76.00, 3.27),
(13, 'Happier Than Ever', 'album', 2021, 3, NULL, 'projekt_6a25d7a76483f.png', 79.00, 3.34),
(14, 'Guitar Songs', 'ep', 2022, 3, NULL, 'projekt_6a25d7bd26a7d.png', 81.00, 3.49),
(15, 'HIT ME HARD AND SOFT', 'album', 2024, 3, NULL, 'projekt_6a25d7d27439a.png', 82.00, 3.60),
(16, 'Blue Lines', 'album', 1991, NULL, 3, 'projekt_6a25d81625281.jpg', 82.00, 3.77),
(17, 'Protection', 'album', 1994, NULL, 3, 'projekt_6a25d82acaeb6.jpg', 79.00, 3.65),
(18, 'Mezzanine', 'album', 1998, NULL, 3, 'projekt_6a25d8a90865f.png', 89.00, 4.11),
(19, '100th Window', 'album', 2003, NULL, 3, 'projekt_6a25d8e0e98df.jpg', 75.00, 3.51),
(20, 'Danny the Dog', 'soundtrack', 2004, NULL, 3, 'projekt_6a25d90e48284.jpg', 62.00, 2.82),
(21, 'Splitting the Atom', 'ep', 2009, NULL, 3, 'projekt_6a25d9536d0fe.jpg', 68.00, 3.10),
(22, 'Heligoland', 'album', 2010, NULL, 3, 'projekt_6a25d96bd8de8.jpg', 76.00, 3.36),
(23, 'Ritual Spirit', 'ep', 2016, NULL, 2, 'projekt_6a25d992ef205.png', 78.00, 3.53),
(24, 'Pretty Hate Machine', 'album', 1989, NULL, 2, 'projekt_6a25d9bca1bfe.png', 82.00, 3.76),
(25, 'Broken', 'ep', 1992, NULL, 2, 'projekt_6a25d9d560a1b.jpg', 85.00, 3.94),
(26, 'The Downward Spiral', 'album', 1994, NULL, 2, 'projekt_6a25d9f56b9c7.png', 90.00, 4.11),
(27, 'The Fragile', 'album', 1999, NULL, 2, 'projekt_6a25da16e2422.jpg', 88.00, 4.01),
(28, 'Quake', 'soundtrack', 1996, NULL, 2, 'projekt_6a25da3bf180d.jpg', 76.00, 3.66),
(29, 'Still', 'album', 2002, NULL, 2, 'projekt_6a25da5876d29.jpg', 83.00, 3.78),
(30, 'With Teeth', 'album', 2005, NULL, 2, 'projekt_6a25da78a0bea.jpg', 80.00, 3.52),
(31, 'Year Zero', 'album', 2007, NULL, 2, 'projekt_6a25da94cccf8.png', 79.00, 3.53),
(32, 'Ghosts I-IV', 'album', 2008, NULL, 2, 'projekt_6a25dac0d21cf.png', 69.00, 3.39),
(33, 'The Slip', 'album', 2008, NULL, 2, 'projekt_6a25dad56f05f.png', 75.00, 3.24),
(34, 'Hesitation Marks', 'album', 2013, NULL, 2, 'projekt_6a25daef06b6d.png', 75.00, 3.32),
(35, 'Not the Actual Events', 'ep', 2016, NULL, 2, 'projekt_6a25db094f92c.jpg', 78.00, 3.50),
(36, 'Add Violence', 'ep', 2017, NULL, 2, 'projekt_6a25db1e47186.jpg', 76.00, 3.53),
(37, 'Bad Witch', 'album', 2018, NULL, 2, 'projekt_6a25db3986d3a.jpg', 77.00, 3.55),
(38, 'Ghosts V: Together', 'album', 2020, NULL, 2, 'projekt_6a25db4f22b15.jpg', 72.00, 3.50),
(39, 'Ghosts VI: Locusts', 'album', 2020, NULL, 2, 'projekt_6a25db5e0e202.jpg', 68.00, 3.41),
(40, 'TRON: Ares', 'soundtrack', 2025, NULL, 2, 'projekt_6a25db788a108.jpg', 75.00, 3.26);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `recenzje`
--

CREATE TABLE `recenzje` (
  `id` int(11) NOT NULL,
  `id_projektu` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `ocena` int(11) NOT NULL,
  `tresc` text NOT NULL,
  `data_dodania` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ulubione`
--

CREATE TABLE `ulubione` (
  `id` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `typ` enum('projekt','artysta','zespol') NOT NULL,
  `id_obiektu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `login`, `haslo`, `is_admin`, `email`) VALUES
(1, 'admin', '$2y$10$oBoExmlQ5D.kvfcwkZhhAOPng46LZ3DLx0Omw3xTvF9sH6yj6rkje', 1, 'admin@gmail.com'),
(2, 'dawd', '$2y$10$653scLCeajSlo2G/m2cUg.cDfnmraj900pF0Y8kgliiapWbd8e14q', 0, 'dawd@gmail.com');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zespoly`
--

CREATE TABLE `zespoly` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `gatunek` varchar(255) DEFAULT NULL,
  `kraj` varchar(255) DEFAULT NULL,
  `rok_zalozenia` int(11) DEFAULT NULL,
  `obecni_czlonkowie` text DEFAULT NULL,
  `zdjecie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `zespoly`
--

INSERT INTO `zespoly` (`id`, `nazwa`, `gatunek`, `kraj`, `rok_zalozenia`, `obecni_czlonkowie`, `zdjecie`) VALUES
(1, 'Justice', 'House, Elektroniczna', 'Francja', 2003, 'Gaspard Augé, Xavier de Rosnay', 'zespol_6a25d5283dd3b.jpg'),
(2, 'Nine Inch Nails', 'Industrial, Metal, Rock', 'Stany Zjednoczone', 1988, 'Trent Reznor, Atticus Ross', 'zespol_6a25d6647947c.jpg'),
(3, 'Massive Attack', 'Trip Hop', 'Wielka Brytania', 1988, 'Robert \"3D\" Del Naja, Grant \"Daddy G\" Marshall', 'zespol_6a25d69040b75.jpg');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `artysci`
--
ALTER TABLE `artysci`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `projekty`
--
ALTER TABLE `projekty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_artysty` (`id_artysty`),
  ADD KEY `id_zespolu` (`id_zespolu`);

--
-- Indeksy dla tabeli `recenzje`
--
ALTER TABLE `recenzje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projektu` (`id_projektu`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `ulubione`
--
ALTER TABLE `ulubione`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `zespoly`
--
ALTER TABLE `zespoly`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artysci`
--
ALTER TABLE `artysci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projekty`
--
ALTER TABLE `projekty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `recenzje`
--
ALTER TABLE `recenzje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ulubione`
--
ALTER TABLE `ulubione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zespoly`
--
ALTER TABLE `zespoly`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projekty`
--
ALTER TABLE `projekty`
  ADD CONSTRAINT `projekty_ibfk_1` FOREIGN KEY (`id_artysty`) REFERENCES `artysci` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projekty_ibfk_2` FOREIGN KEY (`id_zespolu`) REFERENCES `zespoly` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recenzje`
--
ALTER TABLE `recenzje`
  ADD CONSTRAINT `recenzje_ibfk_1` FOREIGN KEY (`id_projektu`) REFERENCES `projekty` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recenzje_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ulubione`
--
ALTER TABLE `ulubione`
  ADD CONSTRAINT `ulubione_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
