<?php
require("session.php");
require("db.php");

$typ = $_POST["typ"] ?? "";
$id_obiektu = filter_var($_POST["id_obiektu"] ?? null, FILTER_VALIDATE_INT);

$dozwolone = ["zespol", "artysta", "projekt"];

if (!in_array($typ, $dozwolone) || !$id_obiektu) {
    echo "blad";
    exit;
}

$id_uzytkownika = $_SESSION["id"];

$stmt = $pdo->prepare("
    SELECT id FROM ulubione
    WHERE id_uzytkownika = ? AND typ = ? AND id_obiektu = ?
");
$stmt->execute([$id_uzytkownika, $typ, $id_obiektu]);
$exists = $stmt->fetchColumn();

if ($exists) {
    $stmt = $pdo->prepare("
        DELETE FROM ulubione
        WHERE id_uzytkownika = ? AND typ = ? AND id_obiektu = ?
    ");
    $stmt->execute([$id_uzytkownika, $typ, $id_obiektu]);
    echo "usunieto";
} else {
    $stmt = $pdo->prepare("
        INSERT INTO ulubione (id_uzytkownika, typ, id_obiektu)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$id_uzytkownika, $typ, $id_obiektu]);
    echo "dodano";
}

exit;
