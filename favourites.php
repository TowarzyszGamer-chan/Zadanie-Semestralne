<?php
require("session.php");
require("db.php");
require("header.php");

$user_id = $_SESSION["id"];

$stmt = $pdo->prepare("
    SELECT * FROM ulubione
    WHERE id_uzytkownika = ?
    ORDER BY typ
");
$stmt->execute([$user_id]);
$fav = $stmt->fetchAll(PDO::FETCH_ASSOC);

$zespoly = [];
$artysci = [];
$projekty = [];

foreach ($fav as $f) {
    if ($f["typ"] === "zespol") $zespoly[] = $f["id_obiektu"];
    if ($f["typ"] === "artysta") $artysci[] = $f["id_obiektu"];
    if ($f["typ"] === "projekt") $projekty[] = $f["id_obiektu"];
}

$lista_zespoly = [];
$lista_artysci = [];
$lista_projekty = [];

if ($zespoly) {
    $in = implode(",", array_fill(0, count($zespoly), "?"));
    $stmt = $pdo->prepare("SELECT * FROM zespoly WHERE id IN ($in)");
    $stmt->execute($zespoly);
    $lista_zespoly = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($artysci) {
    $in = implode(",", array_fill(0, count($artysci), "?"));
    $stmt = $pdo->prepare("SELECT * FROM artysci WHERE id IN ($in)");
    $stmt->execute($artysci);
    $lista_artysci = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($projekty) {
    $in = implode(",", array_fill(0, count($projekty), "?"));
    $stmt = $pdo->prepare("SELECT * FROM projekty WHERE id IN ($in)");
    $stmt->execute($projekty);
    $lista_projekty = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulubione</title>
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts/app.js"></script>
</head>
<body>

<h2>Ulubione</h2>


<h3>Zespoły</h3>

<?php if (!$lista_zespoly): ?>
    <p>Brak ulubionych zespołów.</p>
<?php else: ?>
<ul>
    <?php foreach ($lista_zespoly as $z): ?>
        <li style="margin-bottom: 1rem;">

            <?php if (!empty($z["zdjecie"])): ?>
                <img src="zdjecia/zespoly/thumbs/<?= h($z["zdjecie"]) ?>"
                     class="edit-thumb"
                     style="max-width: 80px;">
            <?php endif; ?>

            <a href="zespol_details.php?id=<?= h($z["id"]) ?>">
                <?= h($z["nazwa"]) ?>
            </a>

            <button class="fav-toggle"
                    data-typ="zespol"
                    data-id="<?= h($z["id"]) ?>"
                    data-liked="1">
                Usuń z ulubionych ❤️
            </button>

        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>


<h3>Artyści</h3>

<?php if (!$lista_artysci): ?>
    <p>Brak ulubionych artystów.</p>
<?php else: ?>
<ul>
    <?php foreach ($lista_artysci as $a): ?>
        <li style="margin-bottom: 1rem;">

            <?php if (!empty($a["zdjecie"])): ?>
                <img src="zdjecia/artysci/thumbs/<?= h($a["zdjecie"]) ?>"
                     class="edit-thumb"
                     style="max-width: 80px;">
            <?php endif; ?>

            <a href="artysta_details.php?id=<?= h($a["id"]) ?>">
                <?= h($a["pseudonim"] ?: ($a["imie"] . " " . $a["nazwisko"])) ?>
            </a>

            <button class="fav-toggle"
                    data-typ="artysta"
                    data-id="<?= h($a["id"]) ?>"
                    data-liked="1">
                Usuń z ulubionych ❤️
            </button>

        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<h3>Projekty</h3>

<?php if (!$lista_projekty): ?>
    <p>Brak ulubionych projektów.</p>
<?php else: ?>
<ul>
    <?php foreach ($lista_projekty as $p): ?>
        <li style="margin-bottom: 1rem;">

            <?php if (!empty($p["okladka"])): ?>
                <img src="okladki/thumbs/<?= h($p["okladka"]) ?>"
                     class="edit-thumb"
                     style="max-width: 80px;">
            <?php endif; ?>

            <a href="projekt_details.php?id=<?= h($p["id"]) ?>">
                <?= h($p["tytul"]) ?> (<?= h($p["rok"]) ?>)
            </a>

            <button class="fav-toggle"
                    data-typ="projekt"
                    data-id="<?= h($p["id"]) ?>"
                    data-liked="1">
                Usuń z ulubionych ❤️
            </button>

        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

</body>
</html>

