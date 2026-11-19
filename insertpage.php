<?php
session_start();
if (empty($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require("dbConnect.php");

$title = trim($_POST["title"] ?? "");
$summary = trim($_POST["summary"] ?? "");
$content = trim($_POST["content"] ?? "");

if ($title !== "" && $summary !== "" && $content !== "") {
    $stmt = $connection->prepare("INSERT INTO news (title, summary, content, published_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $title, $summary, $content);
    $stmt->execute();
}

header("Location: admin.php");
exit;