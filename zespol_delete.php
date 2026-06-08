<?php
require("session.php");
require("db.php");

if ($_SESSION["is_admin"] != 1) {
    exit("Brak uprawnień.");
}

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID.");

$stmt = $pdo->prepare("SELECT zdjecie FROM zespoly WHERE id = ?");
$stmt->execute([$id]);
$z = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$z) exit("Nie znaleziono zespołu.");

$zdjecie = $z["zdjecie"];

$stmt = $pdo->prepare("SELECT id, okladka FROM projekty WHERE id_zespolu = ?");
$stmt->execute([$id]);
$projekty = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($projekty as $p) {
    if ($p["okladka"]) {
        @unlink("okladki/" . $p["okladka"]);
        @unlink("okladki/thumbs/" . $p["okladka"]);
    }


    $del = $pdo->prepare("DELETE FROM projekty WHERE id = ?");
    $del->execute([$p["id"]]);
}

if ($zdjecie) {
    @unlink("zdjecia/zespoly/" . $zdjecie);
    @unlink("zdjecia/zespoly/thumbs/" . $zdjecie);
}

$stmt = $pdo->prepare("DELETE FROM zespoly WHERE id = ?");
$stmt->execute([$id]);

header("Location: zespoly.php");
exit;




