<?php
require("session.php");
require("db.php");

$id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    echo "blad";
    exit;
}

$stmt = $pdo->prepare("SELECT id_uzytkownika FROM recenzje WHERE id = ?");
$stmt->execute([$id]);
$rec = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rec) {
    echo "blad";
    exit;
}

if ($rec["id_uzytkownika"] != $_SESSION["id"] && $_SESSION["is_admin"] != 1) {
    echo "brak_uprawnien";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM recenzje WHERE id = ?");
$stmt->execute([$id]);

echo "ok";
exit;
