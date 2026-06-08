<?php
require("bootstrap.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET" && !empty($_GET["id_projektu"])) {
    $id = filter_var($_GET["id_projektu"], FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid project ID"], 400);

    $stmt = $pdo->prepare("
        SELECT r.*, u.login
        FROM recenzje r
        JOIN uzytkownicy u ON r.id_uzytkownika = u.id
        WHERE r.id_projektu = ?
        ORDER BY r.data_dodania DESC
    ");
    $stmt->execute([$id]);

    respond($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === "POST") {
    $data = get_json();

    if (empty($data["id_projektu"]) || empty($data["id_uzytkownika"]) || empty($data["ocena"]))
        respond(["error" => "Missing fields"], 400);

    $stmt = $pdo->prepare("
        INSERT INTO recenzje (id_projektu, id_uzytkownika, ocena, tresc)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $data["id_projektu"],
        $data["id_uzytkownika"],
        $data["ocena"],
        $data["tresc"] ?? ""
    ]);

    respond(["success" => true], 201);
}

if ($method === "DELETE") {
    $id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id) respond(["error" => "Invalid ID"], 400);

    $stmt = $pdo->prepare("DELETE FROM recenzje WHERE id = ?");
    $stmt->execute([$id]);

    respond(["success" => true]);
}

respond(["error" => "Unsupported method"], 405);
