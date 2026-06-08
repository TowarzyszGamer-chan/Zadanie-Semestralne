<?php
require("session.php");
require("db.php");

if ($_SESSION["is_admin"] != 1) exit("Brak uprawnień.");

$id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
if (!$id) exit("Błędne ID.");

$id_artysty = $_POST["id_artysty"] ?: null;
$id_zespolu = $_POST["id_zespolu"] ?: null;
$tytul = trim($_POST["tytul"] ?? "");
$typ = trim($_POST["typ"] ?? "");
$rok = trim($_POST["rok"] ?? "");
$ocena_aoty = trim($_POST["ocena_aoty"] ?? "");
$ocena_rym = trim($_POST["ocena_rym"] ?? "");

if ($tytul === "") exit("Tytuł jest wymagany.");
if (!is_numeric($rok) || $rok < 1900 || $rok > date("Y")) exit("Nieprawidłowy rok.");

$stmt = $pdo->prepare("SELECT okladka FROM projekty WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch(PDO::FETCH_ASSOC);
$oldCover = $old["okladka"];

$dir = "okladki";
$thumbs = "okladki/thumbs";

if (!is_dir($dir)) mkdir($dir, 0777, true);
if (!is_dir($thumbs)) mkdir($thumbs, 0777, true);

$plik = $_FILES["okladka"] ?? null;
$newCover = $oldCover;

if ($plik && $plik["error"] === 0 && is_uploaded_file($plik["tmp_name"])) {

    $ext = strtolower(pathinfo($plik["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif", "webp"];
    if (!in_array($ext, $allowed)) exit("Nieobsługiwany format pliku.");

    $finalName = uniqid("projekt_") . "." . $ext;
    $fullPath = "$dir/$finalName";
    $thumbPath = "$thumbs/$finalName";

    if (!move_uploaded_file($plik["tmp_name"], $fullPath)) exit("Błąd zapisu pliku.");

    $info = @getimagesize($fullPath);
    if ($info) {
        list($w, $h) = $info;
        if ($w > 0 && $h > 0) {
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
    }

    if ($oldCover) {
        @unlink("$dir/$oldCover");
        @unlink("$thumbs/$oldCover");
    }

    $newCover = $finalName;
}

$stmt = $pdo->prepare("
    UPDATE projekty
    SET id_artysty=?, id_zespolu=?, tytul=?, typ=?, rok=?, ocena_aoty=?, ocena_rym=?, okladka=?
    WHERE id=?
");

$stmt->execute([
    $id_artysty ?: null,
    $id_zespolu ?: null,
    $tytul,
    $typ,
    $rok,
    $ocena_aoty ?: null,
    $ocena_rym ?: null,
    $newCover,
    $id
]);

header("Location: projekt_details.php?id=" . $id);
exit;
