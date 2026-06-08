<?php
require("admin_guard.php");
require("db.php");

$id = $_GET["id"] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    exit("Nieprawidłowe ID.");
}

$stmt = $pdo->prepare("
    SELECT *
    FROM projekty
    WHERE id = ?
");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    exit("Nie znaleziono projektu.");
}

$artysci = $pdo->query("SELECT id, imie, nazwisko, pseudonim FROM artysci ORDER BY nazwisko, imie")->fetchAll(PDO::FETCH_ASSOC);
$zespoly = $pdo->query("SELECT id, nazwa FROM zespoly ORDER BY nazwa")->fetchAll(PDO::FETCH_ASSOC);

if (empty($_SESSION["csrf_projekt_edit"])) {
    $_SESSION["csrf_projekt_edit"] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION["csrf_projekt_edit"];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja projektu</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php require("header.php"); ?>

<h2>Edycja projektu</h2>

<form method="post" action="projekt_update.php" enctype="multipart/form-data" class="form-entity">

    <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">
    <input type="hidden" name="id" value="<?= h($p["id"]) ?>">

    <label>Tytuł:
        <input type="text" name="tytul" value="<?= h($p["tytul"]) ?>" required maxlength="200">
    </label>

    <label>Typ:
        <select name="typ" required>
            <option value="album"      <?= $p["typ"] === "album" ? "selected" : "" ?>>Album</option>
            <option value="ep"         <?= $p["typ"] === "ep" ? "selected" : "" ?>>EP</option>
            <option value="soundtrack" <?= $p["typ"] === "soundtrack" ? "selected" : "" ?>>Soundtrack</option>
        </select>
    </label>

    <label>Rok:
        <input type="number" name="rok" min="1900" max="2100" value="<?= h($p["rok"]) ?>">
    </label>

    <label>Artysta:
        <select name="id_artysty">
            <option value="">— brak —</option>
            <?php foreach ($artysci as $a): ?>
                <option value="<?= $a["id"] ?>" <?= ($p["id_artysty"] == $a["id"]) ? "selected" : "" ?>>
                    <?= h($a["pseudonim"] ?: ($a["imie"] . " " . $a["nazwisko"])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Zespół:
        <select name="id_zespolu">
            <option value="">— brak —</option>
            <?php foreach ($zespoly as $z): ?>
                <option value="<?= $z["id"] ?>" <?= ($p["id_zespolu"] == $z["id"]) ? "selected" : "" ?>>
                    <?= h($z["nazwa"]) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Ocena AOTY:
        <input type="number" name="ocena_aoty" min="0" max="100" value="<?= h($p["ocena_aoty"]) ?>">
    </label>

    <label>Ocena RYM:
        <input type="number" step="0.01" name="ocena_rym" min="0" max="10" value="<?= h($p["ocena_rym"]) ?>">
    </label>

    <label>Aktualna okładka:</label>
    <?php if ($p["okladka"]): ?>
        <img src="okladki/thumbs/<?= h($p["okladka"]) ?>" class="edit-thumb">
    <?php else: ?>
        <p>Brak okładki.</p>
    <?php endif; ?>

    <label>Nowa okładka (opcjonalnie):
        <input type="file" name="okladka" accept="image/*">
    </label>

    <button type="submit">Zapisz zmiany</button>
</form>

</body>
</html>
