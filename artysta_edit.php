<?php
require("admin_guard.php");
require("db.php");

$id = $_GET["id"] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    echo "Nieprawidłowe ID.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM artysci WHERE id = ?");
$stmt->execute([$id]);
$a = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$a) {
    echo "Nie znaleziono artysty.";
    exit;
}

if (empty($_SESSION["csrf_artysta_edit"])) {
    $_SESSION["csrf_artysta_edit"] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION["csrf_artysta_edit"];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja artysty</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php require("header.php"); ?>

<h2>Edycja artysty</h2>

<form method="post" action="artysta_update.php" class="form-entity">

    <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
    <input type="hidden" name="id" value="<?= h($a["id"]) ?>">

    <label>Imię:
        <input type="text" name="imie" value="<?= h($a["imie"]) ?>" required maxlength="80">
    </label>

    <label>Nazwisko:
        <input type="text" name="nazwisko" value="<?= h($a["nazwisko"]) ?>" required maxlength="80">
    </label>

    <label>Pseudonim:
        <input type="text" name="pseudonim" value="<?= h($a["pseudonim"]) ?>" maxlength="120">
    </label>

    <label>Data urodzenia:
        <input type="date" name="data_urodzenia" value="<?= h($a["data_urodzenia"]) ?>" required>
    </label>


    <label>Kraj:
        <input type="text" name="kraj" maxlength="80" value="<?= h($a["kraj"]) ?>">
    </label>

    <label>Gatunek:
        <input type="text" name="gatunek" maxlength="120" value="<?= h($a["gatunek"]) ?>" required>
    </label>


    <label>Opis / biografia:
        <textarea name="opis" rows="4"><?= h($a["opis"]) ?></textarea>
    </label>

    <label>Zdjęcie (opcjonalnie):
        <input type="file" name="zdjecie" accept="image/*">
    </label>

    <?php if (!empty($a["zdjecie"])): ?>
        <img class="edit-thumb" src="zdjecia/artysci/<?= h($a["zdjecie"]) ?>" alt="">
    <?php endif; ?>

    <button type="submit">Zapisz zmiany</button>
</form>

</body>
</html>
