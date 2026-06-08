<?php
require("session.php");
require("db.php");

$stmt = $pdo->query("SELECT * FROM artysci ORDER BY nazwisko, imie");
$artysci = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Artyści</title>
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts/app.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<h2>Artyści</h2>

<div class="search-container">
    <input type="text" id="live-search" placeholder="Szukaj projektów, artystów, zespołów...">
    <div id="live-results"></div>
</div>

<table class="table-list">
    <tr>
        <th>Zdjęcie</th>
        <th>Nazwa</th>
        <th>Gatunek</th>
        <th>Kraj</th>
        <th>Data ur.</th>
        <th>Akcje</th>
    </tr>

    <?php foreach ($artysci as $a): ?>
        <tr>
            <td>
                <?php if (!empty($a["zdjecie"])): ?>
                    <img class="edit-thumb" src="zdjecia/artysci/thumbs/<?= h($a["zdjecie"]) ?>" alt="">
                <?php else: ?>
                    <span class="no-thumb">—</span>
                <?php endif; ?>
            </td>

            <td>
                <a href="artysta_details.php?id=<?= h($a["id"]) ?>">
                    <?= h($a["pseudonim"] ?: ($a["imie"] . " " . $a["nazwisko"])) ?>
                </a>
            </td>

            <td><?= h($a["gatunek"]) ?></td>
            <td><?= h($a["kraj"]) ?></td>
            <td><?= h($a["data_urodzenia"]) ?></td>

            <td>
                <?php if ($_SESSION["is_admin"] == 1): ?>
                    <a href="artysta_edit.php?id=<?= h($a["id"]) ?>">Edytuj</a> |
                    <a href="artysta_delete.php?id=<?= h($a["id"]) ?>" onclick="return confirm('Usunąć artystę?')">Usuń</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
