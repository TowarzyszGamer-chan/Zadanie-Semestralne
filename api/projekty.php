<?php
require("bootstrap.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET" && empty($_GET["id"])) {
    $stmt = $pdo->query("
        SELECT p.*, a.pseudonim, a.imie, a.nazwisko, z.nazwa AS zespol
        FROM projekty p
        LEFT JOIN artysci a ON p.id_artysty = a.id
        LEFT JOIN zespoly z ON p.id_zespolu = z.id
        ORDER BY p.rok ASC
    ");
    respond($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === "GET" && !empty($_GET["id"])) {
    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("
        SELECT p.*, a.pseudonim, a.imie, a.nazwisko, z.nazwa AS zespol
        FROM projekty p
        LEFT JOIN artysci a ON p.id_artysty = a.id
        LEFT JOIN zespoly z ON p.id_zespolu = z.id
        WHERE p.id = ?
    ");
    $stmt->execute([$id]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$p) respond(["error" => "Not found"], 404);

    respond($p);
}

if ($method === "POST") {
    $data = get_json();

    if (empty($data["tytul"])) respond(["error" => "Missing title"], 400);

    $stmt = $pdo->prepare("
        INSERT INTO projekty (id_artysty, id_zespolu, tytul, typ, rok, ocena_aoty, ocena_rym)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data["id_artysty"] ?? null,
        $data["id_zespolu"] ?? null,
        $data["tytul"],
        $data["typ"] ?? null,
        $data["rok"] ?? null,
        $data["ocena_aoty"] ?? null,
        $data["ocena_rym"] ?? null
    ]);

    respond(["success" => true, "id" => $pdo->lastInsertId()], 201);
}

if ($method === "PUT") {
    $data = get_json();

    $id = filter_var($data["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("
        UPDATE projekty
        SET id_artysty=?, id_zespolu=?, tytul=?, typ=?, rok=?, ocena_aoty=?, ocena_rym=?
        WHERE id=?
    ");

    $stmt->execute([
        $data["id_artysty"],
        $data["id_zespolu"],
        $data["tytul"],
        $data["typ"],
        $data["rok"],
        $data["ocena_aoty"],
        $data["ocena_rym"],
        $id
    ]);

    respond(["success" => true]);
}

if ($method === "DELETE") {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("DELETE FROM projekty WHERE id = ?");
    $stmt->execute([$id]);

    respond(["success" => true]);
}

respond(["error" => "Unsupported method"], 405);
