<?php
require("session.php");
require("db.php");

$imie = trim($_POST["imie"] ?? "");
$nazwisko = trim($_POST["nazwisko"] ?? "");
$pseudonim = trim($_POST["pseudonim"] ?? "");
$kraj = trim($_POST["kraj"] ?? "");
$gatunek = trim($_POST["gatunek"] ?? "");
$dataSQL = trim($_POST["data_urodzenia"] ?? "");
$opis = trim($_POST["opis"] ?? "");

$errors = [];

if ($imie === "") $errors[] = "Imię jest wymagane.";
if ($nazwisko === "") $errors[] = "Nazwisko jest wymagane.";
if ($gatunek === "") $errors[] = "Gatunek jest wymagany.";

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataSQL)) {
    $errors[] = "Data musi być w formacie YYYY-MM-DD.";
}

if ($errors) {
    echo implode("<br>", array_map("h", $errors));
    exit;
}

$dir = "zdjecia/artysci";
$thumbs = "zdjecia/artysci/thumbs";

if (!is_dir($dir)) mkdir($dir, 0777, true);
if (!is_dir($thumbs)) mkdir($thumbs, 0777, true);

$plik = $_FILES["zdjecie"] ?? null;
$zdjecieDoBazy = null;

if ($plik && $plik["error"] === 0 && is_uploaded_file($plik["tmp_name"])) {

    $ext = strtolower(pathinfo($plik["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif", "webp"];
    if (!in_array($ext, $allowed)) exit("Nieobsługiwany format pliku.");

    $finalName = uniqid("artysta_") . "." . $ext;

    $fullPath = "$dir/$finalName";
    $thumbPath = "$thumbs/$finalName";

    move_uploaded_file($plik["tmp_name"], $fullPath);

    $info = @getimagesize($fullPath);
    if ($info) {
        list($w, $h) = $info;
        $newW = 200;
        $newH = intval($h * ($newW / $w));

        $thumb = imagecreatetruecolor($newW, $newH);

        switch ($info[2]) {
            case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($fullPath); break;
            case IMAGETYPE_PNG:  $src = imagecreatefrompng($fullPath); break;
            case IMAGETYPE_GIF:  $src = imagecreatefromgif($fullPath); break;
            case IMAGETYPE_WEBP: $src = imagecreatefromwebp($fullPath); break;
            default: $src = null;
        }

        if ($src) {
            imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

            switch ($info[2]) {
                case IMAGETYPE_JPEG: imagejpeg($thumb, $thumbPath, 85); break;
                case IMAGETYPE_PNG:  imagepng($thumb, $thumbPath); break;
                case IMAGETYPE_GIF:  imagegif($thumb, $thumbPath); break;
                case IMAGETYPE_WEBP: imagewebp($thumb, $thumbPath, 85); break;
            }

            imagedestroy($src);
            imagedestroy($thumb);
        }
    }

    $zdjecieDoBazy = $finalName;
}

$stmt = $pdo->prepare("
    INSERT INTO artysci (imie, nazwisko, pseudonim, kraj, gatunek, data_urodzenia, opis, zdjecie)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $imie,
    $nazwisko,
    $pseudonim,
    $kraj,
    $gatunek,
    $dataSQL,
    $opis,
    $zdjecieDoBazy
]);

header("Location: artysci.php");
exit;

