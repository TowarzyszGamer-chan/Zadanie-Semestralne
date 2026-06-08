<?php
require("bootstrap.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET" && empty($_GET["id"])) {
    $stmt = $pdo->query("SELECT * FROM artysci ORDER BY nazwisko, imie");
    respond($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === "GET" && !empty($_GET["id"])) {
    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("SELECT * FROM artysci WHERE id = ?");
    $stmt->execute([$id]);
    $a = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$a) respond(["error" => "Not found"], 404);

    respond($a);
}

if ($method === "POST") {
    $data = get_json();

    if (empty($data["imie"])) respond(["error" => "Missing name"], 400);

    $stmt = $pdo->prepare("
        INSERT INTO artysci (imie, nazwisko, pseudonim, rok_urodzenia, kraj, opis)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data["imie"],
        $data["nazwisko"] ?? null,
        $data["pseudonim"] ?? null,
        $data["rok_urodzenia"] ?? null,
        $data["kraj"] ?? null,
        $data["opis"] ?? null
    ]);

    respond(["success" => true, "id" => $pdo->lastInsertId()], 201);
}

if ($method === "PUT") {
    $data = get_json();

    $id = filter_var($data["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("
        UPDATE artysci
        SET imie=?, nazwisko=?, pseudonim=?, rok_urodzenia=?, kraj=?, opis=?
        WHERE id=?
    ");

    $stmt->execute([
        $data["imie"],
        $data["nazwisko"],
        $data["pseudonim"],
        $data["rok_urodzenia"],
        $data["kraj"],
        $data["opis"],
        $id
    ]);

    respond(["success" => true]);
}

if ($method === "DELETE") {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("DELETE FROM artysci WHERE id = ?");
    $stmt->execute([$id]);

    respond(["success" => true]);
}

respond(["error" => "Unsupported method"], 405);
