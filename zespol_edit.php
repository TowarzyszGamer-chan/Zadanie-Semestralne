<?php
require("admin_guard.php");
require("db.php");

$id = $_GET["id"] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    exit("Nieprawidłowe ID.");
}

$stmt = $pdo->prepare("SELECT * FROM zespoly WHERE id = ?");
$stmt->execute([$id]);
$z = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$z) {
    exit("Nie znaleziono zespołu.");
}

if (empty($_SESSION["csrf_zespol_edit"])) {
    $_SESSION["csrf_zespol_edit"] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION["csrf_zespol_edit"];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja zespołu</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php require("header.php"); ?>

<h2>Edycja zespołu</h2>

<form method="post" action="zespol_update.php" enctype="multipart/form-data" class="form-entity">

    <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
    <input type="hidden" name="id" value="<?= h($z["id"]) ?>">

    <label>Nazwa:
        <input type="text" name="nazwa" value="<?= h($z["nazwa"]) ?>" required maxlength="120">
    </label>

    <label>Gatunek:
        <input type="text" name="gatunek" value="<?= h($z["gatunek"]) ?>" required maxlength="80">
    </label>

    <label>Rok założenia:
        <input type="number" name="rok_zalozenia" min="1900" max="2100"
               value="<?= h($z["rok_zalozenia"]) ?>">
    </label>

    <label>Kraj:
        <input type="text" name="kraj" maxlength="80" value="<?= h($z["kraj"]) ?>">
    </label>

    <label>Obecni członkowie:
        <textarea name="obecni_czlonkowie" rows="4"><?= h($z["obecni_czlonkowie"]) ?></textarea>
    </label>

    <label>Aktualne zdjęcie:</label>
    <?php if ($z["zdjecie"]): ?>
        <img src="zdjecia/zespoly/thumbs/<?= h($z["zdjecie"]) ?>" class="edit-thumb">
    <?php else: ?>
        <p>Brak zdjęcia.</p>
    <?php endif; ?>

    <label>Nowe zdjęcie (opcjonalnie):
        <input type="file" name="zdjecie" accept="image/*">
    </label>

    <button type="submit">Zapisz zmiany</button>
</form>

</body>
</html>


