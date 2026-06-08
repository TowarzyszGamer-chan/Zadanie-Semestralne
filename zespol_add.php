<?php
require("admin_guard.php");
require("db.php");

if (empty($_SESSION["csrf_token_zespol"])) {
    $_SESSION["csrf_token_zespol"] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION["csrf_token_zespol"];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj zespół</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php require("header.php"); ?>

<h2>Dodaj zespół</h2>

<form method="post" action="zespol_insert.php" enctype="multipart/form-data" class="form-entity">
    <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">

    <label>Nazwa:
        <input type="text" name="nazwa" required maxlength="120">
    </label>

    <label>Gatunek:
        <input type="text" name="gatunek" required maxlength="80">
    </label>

    <label>Rok założenia:
        <input type="number" name="rok_zalozenia" min="1900" max="2100">
    </label>

    <label>Kraj:
        <input type="text" name="kraj" maxlength="80">
    </label>

    <label>Obecni członkowie:
        <textarea name="obecni_czlonkowie" rows="4"></textarea>
    </label>

    <label>Zdjęcie zespołu:
        <input type="file" name="zdjecie" accept="image/*">
    </label>

    <button type="submit">Dodaj</button>
</form>

</body>
</html>
