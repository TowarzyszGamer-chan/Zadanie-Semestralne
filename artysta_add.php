<?php
require("admin_guard.php");
require("db.php");

if (empty($_SESSION["csrf_token_artysta"])) {
    $_SESSION["csrf_token_artysta"] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION["csrf_token_artysta"];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj artystę</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<?php require("header.php"); ?>

<h2>Dodaj artystę</h2>

<form method="post" action="artysta_insert.php" enctype="multipart/form-data" class="form-entity">
    <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">

    <label>Imię:
        <input type="text" name="imie" required maxlength="100">
    </label>

    <label>Nazwisko:
        <input type="text" name="nazwisko" required maxlength="100">
    </label>

    <label>Pseudonim (opcjonalnie):
        <input type="text" name="pseudonim" maxlength="150">
    </label>

    <label>Kraj:
        <input type="text" name="kraj" required maxlength="100">
    </label>

    <label>Gatunek:
        <input type="text" name="gatunek" maxlength="120" required>
    </label>

    <label>Data urodzenia:
        <input type="date" name="data_urodzenia" required>
    </label>

    <label>Opis (opcjonalnie):
        <textarea name="opis" rows="5"></textarea>
    </label>

    <label>Zdjęcie (opcjonalnie):
        <input type="file" name="zdjecie" accept="image/*">
    </label>

    <button type="submit">Dodaj</button>
</form>

</body>
</html>

