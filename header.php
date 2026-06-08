<?php
if (!function_exists('h')) {
    function h($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logged_in = isset($_SESSION["login"]);
?>
<header>
    <nav class="main-nav">
        <?php if ($logged_in): ?>
            <a href="index.php">Strona główna</a>
            <a href="zespoly.php">Zespoły</a>
            <a href="artysci.php">Artyści</a>
            <a href="projekty.php">Projekty</a>
            <a href="my_reviews.php">Moje recenzje</a>
            <a href="favourites.php">Ulubione</a>

            <?php if (!empty($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
                <a href="zespol_add.php">[Admin] Dodaj zespół</a>
                <a href="artysta_add.php">[Admin] Dodaj artystę</a>
                <a href="projekt_add.php">[Admin] Dodaj projekt</a>
            <?php endif; ?>

            <span class="welcome">
                Witaj, <strong><?= h($_SESSION["login"] ?? '') ?></strong>
            </span>

            <a href="logout.php">Wyloguj</a>
        <?php else: ?>
            <a href="login.php">Logowanie</a>
            <a href="registration.php">Rejestracja</a>
        <?php endif; ?>
    </nav>
</header>

