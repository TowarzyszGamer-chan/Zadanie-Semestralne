<?php
require("bootstrap.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET" && empty($_GET["id"])) {
    $stmt = $pdo->query("SELECT * FROM zespoly ORDER BY nazwa");
    respond($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === "GET" && !empty($_GET["id"])) {
    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("SELECT * FROM zespoly WHERE id = ?");
    $stmt->execute([$id]);
    $z = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$z) respond(["error" => "Not found"], 404);

    respond($z);
}

if ($method === "POST") {
    $data = get_json();

    if (empty($data["nazwa"])) respond(["error" => "Missing name"], 400);

    $stmt = $pdo->prepare("
        INSERT INTO zespoly (nazwa, gatunek, kraj, rok_zalozenia, obecni_czlonkowie)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data["nazwa"],
        $data["gatunek"] ?? null,
        $data["kraj"] ?? null,
        $data["rok_zalozenia"] ?? null,
        $data["obecni_czlonkowie"] ?? null
    ]);

    respond(["success" => true, "id" => $pdo->lastInsertId()], 201);
}

if ($method === "PUT") {
    $data = get_json();

    $id = $data["id"] ?? null;
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("
        UPDATE zespoly
        SET nazwa=?, gatunek=?, kraj=?, rok_zalozenia=?, obecni_czlonkowie=?
        WHERE id=?
    ");

    $stmt->execute([
        $data["nazwa"],
        $data["gatunek"],
        $data["kraj"],
        $data["rok_zalozenia"],
        $data["obecni_czlonkowie"],
        $id
    ]);

    respond(["success" => true]);
}

if ($method === "DELETE") {
    $id = $_GET["id"] ?? null;
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("DELETE FROM zespoly WHERE id = ?");
    $stmt->execute([$id]);

    respond(["success" => true]);
}

respond(["error" => "Unsupported method"], 405);
