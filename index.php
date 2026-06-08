<?php
require("session.php");
require("header.php");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel główny</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<h2>Witaj w panelu muzycznym</h2>

<p class="intro">
    Wybierz jedną z sekcji, aby rozpocząć.  
    Możesz przeglądać zespoły, artystów, projekty, dodawać recenzje, a także zarządzać ulubionymi.
</p>

<section class="dashboard-grid">

    <a href="zespoly.php" class="dash-card">
        <h3>Zespoły</h3>
        <p>Lista zespołów, szczegóły, projekty, ulubione.</p>
    </a>

    <a href="artysci.php" class="dash-card">
        <h3>Artyści</h3>
        <p>Lista artystów, biografie, projekty, ulubione.</p>
    </a>

    <a href="projekty.php" class="dash-card">
        <h3>Projekty</h3>
        <p>Albumy, EP‑ki, soundtracki, recenzje.</p>
    </a>

    <a href="favourites.php" class="dash-card">
        <h3>Ulubione</h3>
        <p>Zespoły, artyści i projekty dodane do ulubionych.</p>
    </a>

    <a href="my_reviews.php" class="dash-card">
        <h3>Moje recenzje</h3>
        <p>Twoje recenzje projektów, możliwość usuwania.</p>
    </a>

    <?php if (!empty($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
        <a href="projekt_add.php" class="dash-card admin-card">
            <h3>[Admin] Dodaj projekt</h3>
            <p>Dodawanie nowych albumów, EP‑ek i soundtracków.</p>
        </a>

        <a href="zespol_add.php" class="dash-card admin-card">
            <h3>[Admin] Dodaj zespół</h3>
            <p>Tworzenie nowych zespołów w bazie.</p>
        </a>

        <a href="artysta_add.php" class="dash-card admin-card">
            <h3>[Admin] Dodaj artystę</h3>
            <p>Dodawanie nowych artystów solowych.</p>
        </a>
    <?php endif; ?>

</section>

</body>
</html>
