<?php
require("session.php");
require("db.php");

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID.");

$stmt = $pdo->prepare("SELECT * FROM zespoly WHERE id = ?");
$stmt->execute([$id]);
$z = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$z) exit("Nie znaleziono zespołu.");

$user_id = $_SESSION["id"];

$stmt = $pdo->prepare("
    SELECT 1 FROM ulubione
    WHERE id_uzytkownika = ? AND typ = 'zespol' AND id_obiektu = ?
");
$stmt->execute([$user_id, $id]);
$liked = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT id, tytul, rok, okladka, typ
    FROM projekty
    WHERE id_zespolu = ?
    ORDER BY rok ASC
");
$stmt->execute([$id]);
$projekty = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= h($z["nazwa"]) ?></title>
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts/app.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<h2><?= h($z["nazwa"]) ?></h2>

<button class="fav-toggle"
        data-typ="zespol"
        data-id="<?= h($z["id"]) ?>"
        data-liked="<?= $liked ? 1 : 0 ?>">
    <?= $liked ? "Usuń z ulubionych ❤️" : "Dodaj do ulubionych 🤍" ?>
</button>

<?php if (!empty($z["zdjecie"])): ?>
    <img class="details-photo"
         src="zdjecia/zespoly/<?= h($z["zdjecie"]) ?>"
         alt="">
<?php endif; ?>

<p><b>Gatunek:</b> <?= h($z["gatunek"]) ?></p>
<p><b>Kraj:</b> <?= h($z["kraj"]) ?></p>
<p><b>Rok założenia:</b> <?= h($z["rok_zalozenia"]) ?></p>
<p><b>Obecni członkowie:</b><br><?= nl2br(h($z["obecni_czlonkowie"])) ?></p>

<?php if ($_SESSION["is_admin"] == 1): ?>
    <p>
        <a href="zespol_edit.php?id=<?= h($z["id"]) ?>">Edytuj</a> |
        <a href="zespol_delete.php?id=<?= h($z["id"]) ?>"
           onclick="return confirm('Usunąć zespół?')">Usuń</a>
    </p>
<?php endif; ?>

<hr>

<h3>Projekty tego zespołu</h3>

<?php if (!$projekty): ?>
    <p>Brak projektów powiązanych z tym zespołem.</p>
<?php else: ?>

<table class="table-list">
    <tr>
        <th>Okładka</th>
        <th>Tytuł</th>
        <th>Typ</th>
        <th>Rok</th>
        <th>Akcje</th>
    </tr>

    <?php foreach ($projekty as $p): ?>
        <tr>
            <td>
                <?php if (!empty($p["okladka"])): ?>
                    <img class="edit-thumb"
                         src="okladki/<?= h($p["okladka"]) ?>"
                         alt="">
                <?php endif; ?>
            </td>

            <td>
                <a href="projekt_details.php?id=<?= h($p["id"]) ?>">
                    <?= h($p["tytul"]) ?>
                </a>
            </td>

            <td><?= h($p["typ"]) ?></td>
            <td><?= h($p["rok"]) ?></td>

            <td>
                <?php if ($_SESSION["is_admin"] == 1): ?>
                    <a href="projekt_edit.php?id=<?= h($p["id"]) ?>">Edytuj</a> |
                    <a href="projekt_delete.php?id=<?= h($p["id"]) ?>"
                       onclick="return confirm('Usunąć projekt?')">Usuń</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php endif; ?>

</body>
</html>

