<?php
require("session.php");
require("db.php");

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID projektu.");

$stmt = $pdo->prepare("
    SELECT p.*, 
           a.imie AS art_imie, a.nazwisko AS art_nazwisko, a.pseudonim AS art_pseudonim,
           z.nazwa AS zespol_nazwa
    FROM projekty p
    LEFT JOIN artysci a ON p.id_artysty = a.id
    LEFT JOIN zespoly z ON p.id_zespolu = z.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) exit("Nie znaleziono projektu.");

$user_id = $_SESSION["id"];

$stmt = $pdo->prepare("
    SELECT 1 FROM ulubione
    WHERE id_uzytkownika = ? AND typ = 'projekt' AND id_obiektu = ?
");
$stmt->execute([$user_id, $id]);
$liked = $stmt->fetchColumn();

$inne_artysty = [];
if (!empty($p["id_artysty"])) {
    $stmt = $pdo->prepare("
        SELECT id, tytul, rok, okladka, typ
        FROM projekty
        WHERE id_artysty = ? AND id != ?
        ORDER BY rok ASC
    ");
    $stmt->execute([$p["id_artysty"], $p["id"]]);
    $inne_artysty = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$inne_zespolu = [];
if (!empty($p["id_zespolu"])) {
    $stmt = $pdo->prepare("
        SELECT id, tytul, rok, okladka, typ
        FROM projekty
        WHERE id_zespolu = ? AND id != ?
        ORDER BY rok ASC
    ");
    $stmt->execute([$p["id_zespolu"], $p["id"]]);
    $inne_zespolu = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $pdo->prepare("
    SELECT r.*, u.login
    FROM recenzje r
    JOIN uzytkownicy u ON r.id_uzytkownika = u.id
    WHERE r.id_projektu = ?
    ORDER BY r.id DESC
");
$stmt->execute([$id]);
$recenzje = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= h($p["tytul"]) ?></title>
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts/app.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<h2><?= h($p["tytul"]) ?></h2>

<button class="fav-toggle"
        data-typ="projekt"
        data-id="<?= h($p["id"]) ?>"
        data-liked="<?= $liked ? 1 : 0 ?>">
    <?= $liked ? "Usuń z ulubionych ❤️" : "Dodaj do ulubionych 🤍" ?>
</button>

<?php if (!empty($p["okladka"])): ?>
    <img class="details-photo"
         src="okladki/<?= h($p["okladka"]) ?>"
         alt="<?= h($p["tytul"]) ?>">
<?php endif; ?>

<p><b>Typ:</b> <?= h($p["typ"]) ?></p>
<p><b>Rok:</b> <?= h($p["rok"]) ?></p>

<?php if ($p["id_artysty"]): ?>
    <p><b>Artysta:</b>
        <a href="artysta_details.php?id=<?= h($p["id_artysty"]) ?>">
            <?= h($p["art_pseudonim"] ?: ($p["art_imie"] . " " . $p["art_nazwisko"])) ?>
        </a>
    </p>
<?php endif; ?>

<?php if ($p["id_zespolu"]): ?>
    <p><b>Zespół:</b>
        <a href="zespol_details.php?id=<?= h($p["id_zespolu"]) ?>">
            <?= h($p["zespol_nazwa"]) ?>
        </a>
    </p>
<?php endif; ?>

<p><b>Ocena AOTY:</b> <?= h($p["ocena_aoty"]) ?><b> / 100</b></p>
<p><b>Ocena RYM:</b> <?= h($p["ocena_rym"]) ?> <b> / 5.00</b></p>

<?php if ($_SESSION["is_admin"] == 1): ?>
    <p>
        <a href="projekt_edit.php?id=<?= h($p["id"]) ?>">Edytuj</a> |
        <a href="projekt_delete.php?id=<?= h($p["id"]) ?>"
           onclick="return confirm('Usunąć projekt?')">Usuń</a>
    </p>
<?php endif; ?>

<hr>

<?php if ($inne_artysty): ?>
<h3>Inne projekty tego artysty</h3>

<table class="table-list">
    <tr>
        <th>Okładka</th>
        <th>Tytuł</th>
        <th>Typ</th>
        <th>Rok</th>
    </tr>

    <?php foreach ($inne_artysty as $i): ?>
        <tr>
            <td>
                <?php if ($i["okladka"]): ?>
                    <img class="edit-thumb" src="okladki/<?= h($i["okladka"]) ?>">
                <?php endif; ?>
            </td>
            <td><a href="projekt_details.php?id=<?= h($i["id"]) ?>"><?= h($i["tytul"]) ?></a></td>
            <td><?= h($i["typ"]) ?></td>
            <td><?= h($i["rok"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if ($inne_zespolu): ?>
<h3>Inne projekty tego zespołu</h3>

<table class="table-list">
    <tr>
        <th>Okładka</th>
        <th>Tytuł</th>
        <th>Typ</th>
        <th>Rok</th>
    </tr>

    <?php foreach ($inne_zespolu as $i): ?>
        <tr>
            <td>
                <?php if ($i["okladka"]): ?>
                    <img class="edit-thumb" src="okladki/<?= h($i["okladka"]) ?>">
                <?php endif; ?>
            </td>
            <td><a href="projekt_details.php?id=<?= h($i["id"]) ?>"><?= h($i["tytul"]) ?></a></td>
            <td><?= h($i["typ"]) ?></td>
            <td><?= h($i["rok"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<hr>

<h3>Recenzje</h3>

<hr>

<h3>Dodaj recenzję</h3>

<?php
$_SESSION["csrf_token_review"] = bin2hex(random_bytes(32));
?>

<form action="recenzje_insert.php" method="post" class="form-entity" style="max-width: 400px;">

    <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token_review"] ?>">
    <input type="hidden" name="id_projektu" value="<?= h($p["id"]) ?>">

    <label>
        Ocena (1–10):
        <input type="number" name="ocena" min="1" max="10" required>
    </label>

    <label>
        Treść recenzji:
        <textarea name="tresc" rows="4" required></textarea>
    </label>

    <button type="submit">Dodaj recenzję</button>
</form>

<hr>



<?php if (!$recenzje): ?>
    <p>Brak recenzji.</p>
<?php else: ?>
    <?php foreach ($recenzje as $r): ?>
    <div class="review-box" id="review-<?= h($r["id"]) ?>">
    <p><b><?= h($r["login"]) ?></b> ocenił na <b><?= h($r["ocena"]) ?>/10</b></p>
    <p><?= nl2br(h($r["tresc"])) ?></p>

    <?php if ($_SESSION["is_admin"] == 1): ?>
        <button class="delete-review"
                data-review-id="<?= h($r["id"]) ?>">
            Usuń recenzję
        </button>
    <?php endif; ?>
    </div>

        <hr>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
