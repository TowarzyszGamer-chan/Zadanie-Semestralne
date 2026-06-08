<?php
require("session.php");
require("db.php");
require("header.php");

$stmt = $pdo->prepare("
    SELECT r.*, p.tytul
    FROM recenzje r
    JOIN projekty p ON r.id_projektu = p.id
    WHERE r.id_uzytkownika = ?
    ORDER BY r.data_dodania DESC
");
$stmt->execute([$_SESSION["id"]]);
$recenzje = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje recenzje</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<h2>Moje recenzje</h2>

<?php if (!$recenzje): ?>
    <p>Nie dodałeś jeszcze żadnych recenzji.</p>
<?php else: ?>

<section class="reviews-list">
<?php foreach ($recenzje as $r): ?>
    <article class="review-card" id="review-<?= $r["id"] ?>">

        <header class="review-header">
            <h3>
                <a href="projekt_details.php?id=<?= $r["id_projektu"] ?>">
                    <?= h($r["tytul"]) ?>
                </a>
            </h3>
            <span class="review-score"><?= h($r["ocena"]) ?>/10</span>
        </header>

        <p><?= nl2br(h($r["tresc"])) ?></p>

        <p class="review-date"><em><?= h($r["data_dodania"]) ?></em></p>

        <button class="delete-review" data-review-id="<?= $r["id"] ?>">
            Usuń
        </button>

    </article>
<?php endforeach; ?>
</section>

<?php endif; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="scripts/app.js"></script>

</body>
</html>
