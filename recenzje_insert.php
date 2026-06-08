<?php
require("session.php");
require("db.php");

if (empty($_POST["csrf_token"]) || $_POST["csrf_token"] !== ($_SESSION["csrf_token_review"] ?? "")) {
    exit("Błąd CSRF.");
}
unset($_SESSION["csrf_token_review"]);

$id_projektu = filter_var($_POST["id_projektu"] ?? null, FILTER_VALIDATE_INT);
$ocena = $_POST["ocena"] ?? null;
$tresc = trim($_POST["tresc"] ?? "");

$errors = [];

if (!$id_projektu) $errors[] = "Nieprawidłowy projekt.";
if (!is_numeric($ocena) || $ocena < 1 || $ocena > 10) $errors[] = "Ocena musi być 1–10.";
if ($tresc === "") $errors[] = "Treść recenzji nie może być pusta.";

if ($errors) {
    echo implode("<br>", array_map("h", $errors));
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO recenzje (id_projektu, id_uzytkownika, ocena, tresc)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([
    $id_projektu,
    $_SESSION["id"],
    $ocena,
    $tresc
]);

header("Location: projekt_details.php?id=" . $id_projektu);
exit;
