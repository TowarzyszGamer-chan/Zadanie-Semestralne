<?php
require("session.php");
require("db.php");

$stmt = $pdo->query("SELECT * FROM zespoly ORDER BY nazwa");
$zespoly = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zespoły</title>
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts/app.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<h2>Zespoły</h2>

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
        <th>Rok</th>
        <th>Akcje</th>
    </tr>

    <?php foreach ($zespoly as $z): ?>
        <tr>
            <td>
                <?php if (!empty($z["zdjecie"])): ?>
                    <img class="edit-thumb" src="zdjecia/zespoly/thumbs/<?= h($z["zdjecie"]) ?>" alt="">
                <?php endif; ?>
            </td>

            <td><a href="zespol_details.php?id=<?= h($z["id"]) ?>"><?= h($z["nazwa"]) ?></a></td>
            <td><?= h($z["gatunek"]) ?></td>
            <td><?= h($z["kraj"]) ?></td>
            <td><?= h($z["rok_zalozenia"]) ?></td>

            <td>
                <?php if ($_SESSION["is_admin"] == 1): ?>
                    <a href="zespol_edit.php?id=<?= h($z["id"]) ?>">Edytuj</a> |
                    <a href="zespol_delete.php?id=<?= h($z["id"]) ?>" onclick="return confirm('Usunąć zespół?')">Usuń</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>






