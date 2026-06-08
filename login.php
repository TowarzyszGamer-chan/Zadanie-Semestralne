<?php
require("db.php");
session_start();

if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

$blad = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"] ?? "");
    $haslo = $_POST["haslo"] ?? "";

    if ($login === "" || $haslo === "") {
        $blad = "Podaj login i hasło.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM uzytkownicy WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($haslo, $user["haslo"])) {
            $_SESSION["login"] = $user["login"];
            $_SESSION["is_admin"] = $user["is_admin"];
            $_SESSION["id"] = $user["id"];
            header("Location: index.php");
            exit;
        } else {
            $blad = "Błędny login lub hasło.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php require("header.php"); ?>

<h2>Logowanie</h2>

<?php if ($blad): ?>
    <p class="error"><?= h($blad) ?></p>
<?php endif; ?>

<form method="post" action="" class="login-form">
    <label>
        Login:
        <input type="text" name="login" required minlength="3" maxlength="50">
    </label>

    <label>
        Hasło:
        <input type="password" name="haslo" required minlength="6">
    </label>

    <button type="submit">Zaloguj</button>
</form>

<p>Nie masz konta? <a href="registration.php">Zarejestruj się</a></p>

</body>
</html>
