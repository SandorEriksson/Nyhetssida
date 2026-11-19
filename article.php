<?php
require("dbConnect.php");

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$article = null;

if ($id) {
    $stmt = $connection->prepare("SELECT title, summary, content, published_date FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $article = $stmt->get_result()->fetch_assoc();
}

if (!$article) {
    http_response_code(404);
    die("Artikeln hittades inte. <a href='index.php'>Tillbaka</a>");
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article["title"]) ?></title>
</head>
<body>
    <a href="index.php">&larr; Alla nyheter</a>
    <article>
        <h1><?= htmlspecialchars($article["title"]) ?></h1>
        <p><strong><?= htmlspecialchars($article["summary"]) ?></strong></p>
        <p>Publicerad: <?= htmlspecialchars($article["published_date"]) ?></p>
        <div><?= nl2br(htmlspecialchars($article["content"])) ?></div>
    </article>
</body>
</html>