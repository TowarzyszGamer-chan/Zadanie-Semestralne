<?php
require("admin_guard.php");
require("db.php");

if (empty($_SESSION["csrf_token_projekt"])) {
    $_SESSION["csrf_token_projekt"] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION["csrf_token_projekt"];

$artysci = $pdo->query("SELECT id, imie, nazwisko, pseudonim FROM artysci ORDER BY nazwisko, imie")->fetchAll(PDO::FETCH_ASSOC);
$zespoly = $pdo->query("SELECT id, nazwa FROM zespoly ORDER BY nazwa")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj projekt</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php require("header.php"); ?>

<h2>Dodaj projekt</h2>

<form method="post" action="projekt_insert.php" enctype="multipart/form-data" class="form-entity">
    <input type="hidden" name="csrf_token" value="<?= h($csrf) ?>">

    <label>Tytuł:
        <input type="text" name="tytul" required maxlength="200">
    </label>

    <label>Typ:
        <select name="typ" required>
            <option value="album">Album</option>
            <option value="ep">EP</option>
            <option value="soundtrack">Soundtrack</option>
        </select>
    </label>

    <label>Rok:
        <input type="number" name="rok" min="1900" max="2100">
    </label>

    <label>Artysta (solo):
        <select name="id_artysty">
            <option value="">— brak —</option>
            <?php foreach ($artysci as $a): ?>
                <option value="<?= $a["id"] ?>">
                    <?= h($a["pseudonim"] ?: ($a["imie"] . " " . $a["nazwisko"])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Zespół:
        <select name="id_zespolu">
            <option value="">— brak —</option>
            <?php foreach ($zespoly as $z): ?>
                <option value="<?= $z["id"] ?>"><?= h($z["nazwa"]) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Okładka:
        <input type="file" name="okladka" accept="image/*">
    </label>

    <label>Ocena AOTY:
        <input type="number" name="ocena_aoty" min="0" max="100">
    </label>

    <label>Ocena RYM:
        <input type="number" step="0.01" name="ocena_rym" min="0" max="10">
    </label>

    <button type="submit">Dodaj</button>
</form>

</body>
</html>
