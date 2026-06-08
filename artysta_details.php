<?php
require("session.php");
require("db.php");

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID.");

$stmt = $pdo->prepare("SELECT * FROM artysci WHERE id = ?");
$stmt->execute([$id]);
$a = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$a) exit("Nie znaleziono artysty.");

$user_id = $_SESSION["id"];

$stmt = $pdo->prepare("
    SELECT 1 FROM ulubione
    WHERE id_uzytkownika = ? AND typ = 'artysta' AND id_obiektu = ?
");
$stmt->execute([$user_id, $id]);
$liked = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT id, tytul, rok, okladka, typ
    FROM projekty
    WHERE id_artysty = ?
    ORDER BY rok ASC
");
$stmt->execute([$id]);
$projekty = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= h($a["pseudonim"] ?: ($a["imie"] . " " . $a["nazwisko"])) ?></title>
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts/app.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<h2><?= h($a["pseudonim"] ?: ($a["imie"] . " " . $a["nazwisko"])) ?></h2>

<button class="fav-toggle"
        data-typ="artysta"
        data-id="<?= h($a["id"]) ?>"
        data-liked="<?= $liked ? 1 : 0 ?>">
    <?= $liked ? "Usuń z ulubionych ❤️" : "Dodaj do ulubionych 🤍" ?>
</button>

<?php if (!empty($a["zdjecie"])): ?>
    <img class="details-photo"
         src="zdjecia/artysci/<?= h($a["zdjecie"]) ?>"
         alt="">
<?php endif; ?>

<p><b>Imię:</b> <?= h($a["imie"]) ?></p>
<p><b>Nazwisko:</b> <?= h($a["nazwisko"]) ?></p>

<?php if ($a["pseudonim"]): ?>
    <p><b>Pseudonim:</b> <?= h($a["pseudonim"]) ?></p>
<?php endif; ?>

<p><b>Kraj:</b> <?= h($a["kraj"]) ?></p>
<p><b>Gatunek:</b> <?= h($a["gatunek"]) ?></p>
<p><b>Data urodzenia:</b> <?= h($a["data_urodzenia"]) ?></p>

<?php if ($a["opis"]): ?>
    <p><b>Opis:</b><br><?= nl2br(h($a["opis"])) ?></p>
<?php endif; ?>

<?php if ($_SESSION["is_admin"] == 1): ?>
    <p>
        <a href="artysta_edit.php?id=<?= h($a["id"]) ?>">Edytuj</a> |
        <a href="artysta_delete.php?id=<?= h($a["id"]) ?>"
           onclick="return confirm('Usunąć artystę?')">Usuń</a>
    </p>
<?php endif; ?>

<hr>

<h3>Projekty tego artysty</h3>

<?php if (!$projekty): ?>
    <p>Brak projektów powiązanych z tym artystą.</p>
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






