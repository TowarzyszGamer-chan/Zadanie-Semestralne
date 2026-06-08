<?php
require("session.php");
require("db.php");

if ($_SESSION["is_admin"] != 1) exit("Brak uprawnień.");

$id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID.");

$imie = trim($_POST["imie"] ?? "");
$nazwisko = trim($_POST["nazwisko"] ?? "");
$pseudonim = trim($_POST["pseudonim"] ?? "");
$kraj = trim($_POST["kraj"] ?? "");
$gatunek = trim($_POST["gatunek"] ?? "");
$dataSQL = trim($_POST["data_urodzenia"] ?? "");
$opis = trim($_POST["opis"] ?? "");

if ($imie === "" || $nazwisko === "" || $gatunek === "") exit("Wymagane pola są puste.");

$stmt = $pdo->prepare("SELECT zdjecie FROM artysci WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch(PDO::FETCH_ASSOC);
$oldPhoto = $old["zdjecie"];

$dir = "zdjecia/artysci";
$thumbs = "zdjecia/artysci/thumbs";

if (!is_dir($dir)) mkdir($dir, 0777, true);
if (!is_dir($thumbs)) mkdir($thumbs, 0777, true);

$plik = $_FILES["zdjecie"] ?? null;
$newPhoto = $oldPhoto;

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

    if ($oldPhoto) {
        @unlink("$dir/$oldPhoto");
        @unlink("$thumbs/$oldPhoto");
    }

    $newPhoto = $finalName;
}

$stmt = $pdo->prepare("
    UPDATE artysci
    SET imie=?, nazwisko=?, pseudonim=?, kraj=?, gatunek=?, data_urodzenia=?, opis=?, zdjecie=?
    WHERE id=?
");

$stmt->execute([
    $imie,
    $nazwisko,
    $pseudonim,
    $kraj,
    $gatunek,
    $dataSQL,
    $opis,
    $newPhoto,
    $id
]);

header("Location: artysta_details.php?id=" . $id);
exit;


