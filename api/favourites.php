<?php
require("bootstrap.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET" && !empty($_GET["id_uzytkownika"])) {
    $id = filter_var($_GET["id_uzytkownika"], FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid user ID"], 400);

    $stmt = $pdo->prepare("
        SELECT * FROM ulubione
        WHERE id_uzytkownika = ?
    ");
    $stmt->execute([$id]);

    respond($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === "POST") {
    $data = get_json();

    $uid = $data["id_uzytkownika"] ?? null;
    $typ = $data["typ"] ?? null;
    $oid = $data["id_obiektu"] ?? null;

    if (!$uid || !$typ || !$oid) respond(["error" => "Missing fields"], 400);

    $stmt = $pdo->prepare("
        SELECT id FROM ulubione
        WHERE id_uzytkownika=? AND typ=? AND id_obiektu=?
    ");
    $stmt->execute([$uid, $typ, $oid]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $stmt = $pdo->prepare("
            DELETE FROM ulubione
            WHERE id_uzytkownika=? AND typ=? AND id_obiektu=?
        ");
        $stmt->execute([$uid, $typ, $oid]);
        respond(["status" => "removed"]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO ulubione (id_uzytkownika, typ, id_obiektu)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$uid, $typ, $oid]);
        respond(["status" => "added"]);
    }
}

respond(["error" => "Unsupported method"], 405);
