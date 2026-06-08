<?php
require("session.php");
require("db.php");

if ($_SESSION["is_admin"] != 1) {
    exit("Brak uprawnień.");
}

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID.");

$stmt = $pdo->prepare("SELECT zdjecie FROM artysci WHERE id = ?");
$stmt->execute([$id]);
$a = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$a) exit("Nie znaleziono artysty.");

$zdjecie = $a["zdjecie"];

$stmt = $pdo->prepare("SELECT id, okladka FROM projekty WHERE id_artysty = ?");
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
    @unlink("zdjecia/artysci/" . $zdjecie);
    @unlink("zdjecia/artysci/thumbs/" . $zdjecie);
}

$stmt = $pdo->prepare("DELETE FROM artysci WHERE id = ?");
$stmt->execute([$id]);

header("Location: artysci.php");
exit;

