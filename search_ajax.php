<?php
require("session.php");
require("db.php");

$fraza = trim($_GET["fraza"] ?? "");

if (strlen($fraza) < 2) {
    echo "";
    exit;
}

$fraza_like = "%" . $fraza . "%";


$stmt = $pdo->prepare("
    SELECT id, nazwa, gatunek
    FROM zespoly
    WHERE nazwa LIKE ?
    ORDER BY nazwa
    LIMIT 10
");
$stmt->execute([$fraza_like]);
$zespoly = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("
    SELECT id, imie, nazwisko, pseudonim
    FROM artysci
    WHERE imie LIKE ? OR nazwisko LIKE ? OR pseudonim LIKE ?
    ORDER BY nazwisko, imie
    LIMIT 10
");
$stmt->execute([$fraza_like, $fraza_like, $fraza_like]);
$artysci = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("
    SELECT id, tytul, rok
    FROM projekty
    WHERE tytul LIKE ?
    ORDER BY rok DESC
    LIMIT 10
");
$stmt->execute([$fraza_like]);
$projekty = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<div class='live-results-box'>";

if (!$zespoly && !$artysci && !$projekty) {
    echo "<p class='no-results'>Brak wyników.</p>";
    echo "</div>";
    exit;
}


if ($zespoly) {
    echo "<h4>Zespoły</h4><ul>";
    foreach ($zespoly as $z) {
        echo "<li><a href='zespol_details.php?id=" . h($z["id"]) . "'>"
            . h($z["nazwa"]) . "</a></li>";
    }
    echo "</ul>";
}


if ($artysci) {
    echo "<h4>Artyści</h4><ul>";
    foreach ($artysci as $a) {
        $nazwa = $a["pseudonim"] ?: ($a["imie"] . " " . $a["nazwisko"]);
        echo "<li><a href='artysta_details.php?id=" . h($a["id"]) . "'>"
            . h($nazwa) . "</a></li>";
    }
    echo "</ul>";
}


if ($projekty) {
    echo "<h4>Projekty</h4><ul>";
    foreach ($projekty as $p) {
        echo "<li><a href='projekt_details.php?id=" . h($p["id"]) . "'>"
            . h($p["tytul"]) . " (" . h($p["rok"]) . ")</a></li>";
    }
    echo "</ul>";
}

echo "</div>";
