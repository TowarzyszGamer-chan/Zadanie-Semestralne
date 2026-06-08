<?php
require("db.php");
session_start();

$blad = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST["login"] ?? "");
    $haslo = trim($_POST["haslo"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if ($login === "" || $haslo === "" || $email === "") {
        $blad = "Wszystkie pola są wymagane.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $blad = "Nieprawidłowy adres e-mail.";
    } elseif (strlen($login) < 3 || strlen($login) > 50) {
        $blad = "Login musi mieć 3–50 znaków.";
    } elseif (strlen($haslo) < 6) {
        $blad = "Hasło musi mieć co najmniej 6 znaków.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM uzytkownicy WHERE login = ?");
        $stmt->execute([$login]);

        if ($stmt->fetch()) {
            $blad = "Taki login już istnieje.";
        } else {
            $hash = password_hash($haslo, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO uzytkownicy (login, haslo, email)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$login, $hash, $email]);

            $success = "Konto zostało utworzone. Możesz się zalogować.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<?php require("header.php"); ?>

<h2>Rejestracja</h2>

<?php if ($blad): ?>
    <p class="error"><?= h($blad) ?></p>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?= h($success) ?></p>
    <p><a href="login.php">Przejdź do logowania</a></p>
<?php else: ?>
<form method="post" action="" class="register-form">
    <label>
        Login:
        <input type="text" name="login" required minlength="3" maxlength="50"
               value="<?= h($login ?? '') ?>">
    </label>

    <label>
        Hasło:
        <input type="password" name="haslo" required minlength="6">
    </label>

    <label>
        Email:
        <input type="email" name="email" required value="<?= h($email ?? '') ?>">
    </label>

    <button type="submit">Zarejestruj</button>
</form>

<p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
<?php endif; ?>

</body>
</html>

