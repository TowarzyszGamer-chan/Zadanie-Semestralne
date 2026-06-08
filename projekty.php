<?php
require("session.php");
require("db.php");

$typy = ["album", "ep", "soundtrack"];

$sql = "SELECT p.*, a.pseudonim, a.imie, a.nazwisko, z.nazwa AS zespol
        FROM projekty p
        LEFT JOIN artysci a ON p.id_artysty = a.id
        LEFT JOIN zespoly z ON p.id_zespolu = z.id
        WHERE 1=1";

$params = [];

if (!empty($_GET["typ"]) && in_array($_GET["typ"], $typy)) {
    $sql .= " AND p.typ = ?";
    $params[] = $_GET["typ"];
}

$sql .= " ORDER BY p.rok ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$projekty = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Projekty</title>
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="scripts/app.js"></script>
</head>
<body>

<?php require("header.php"); ?>

<h2>Projekty</h2>

<div class="search-container">
    <input type="text" id="live-search" placeholder="Szukaj projektów, artystów, zespołów...">
    <div id="live-results"></div>
</div>

<form method="get" class="filter-form">
    <label>Typ:
        <select name="typ">
            <option value="">Wszystkie</option>
            <?php foreach ($typy as $t): ?>
                <option value="<?= h($t) ?>" <?= (($_GET["typ"] ?? "") === $t) ? "selected" : "" ?>>
                    <?= ucfirst($t) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Filtruj</button>
</form>

<table class="table-list">
    <tr>
        <th>Okładka</th>
        <th>Tytuł</th>
        <th>Typ</th>
        <th>Rok</th>
        <th>Artysta / Zespół</th>
        <th>Akcje</th>
    </tr>

    <?php foreach ($projekty as $p): ?>
        <tr>
            <td>
                <?php if ($p["okladka"]): ?>
                    <img src="okladki/thumbs/<?= h($p["okladka"]) ?>" class="thumb-small">
                <?php else: ?>
                    <img src="images/brak-okladki.png" class="thumb-small">
                <?php endif; ?>
            </td>

            <td><a href="projekt_details.php?id=<?= h($p["id"]) ?>"><?= h($p["tytul"]) ?></a></td>
            <td><?= h($p["typ"]) ?></td>
            <td><?= h($p["rok"]) ?></td>

            <td>
                <?php if ($p["pseudonim"] || $p["imie"]): ?>
                    <?= h($p["pseudonim"] ?: ($p["imie"] . " " . $p["nazwisko"])) ?>
                <?php else: ?>
                    <?= h($p["zespol"]) ?>
                <?php endif; ?>
            </td>

            <td>
                <?php if ($_SESSION["is_admin"] == 1): ?>
                    <a href="projekt_edit.php?id=<?= h($p["id"]) ?>">Edytuj</a> |
                    <a href="projekt_delete.php?id=<?= h($p["id"]) ?>" onclick="return confirm('Usunąć projekt?')">Usuń</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>

