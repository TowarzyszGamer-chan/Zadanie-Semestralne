<?php
require("session.php");
require("db.php");

if ($_SESSION["is_admin"] != 1) {
    exit("Brak uprawnień.");
}

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID.");

// Pobranie okładki
$stmt = $pdo->prepare("SELECT okladka FROM projekty WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) exit("Nie znaleziono projektu.");

$okladka = $p["okladka"];

if ($okladka) {
    @unlink("okladki/" . $okladka);
    @unlink("okladki/thumbs/" . $okladka);
}

// Usunięcie projektu
$stmt = $pdo->prepare("DELETE FROM projekty WHERE id = ?");
$stmt->execute([$id]);

header("Location: projekty.php");
exit;


