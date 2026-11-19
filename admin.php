<?php
session_start();

/*
if (empty($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}
*/
require("dbConnect.php");

$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    $title = trim($_POST["title"] ?? "");
    $summary = trim($_POST["summary"] ?? "");
    $content = trim($_POST["content"] ?? "");

    if ($title === "" || $summary === "" || $content === "") {
        $error = "Alla fält måste fyllas i.";
    } else {
        $stmt = $connection->prepare("INSERT INTO news (title, summary, content, published_date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $title, $summary, $content);
        $stmt->execute();
        header("Location: admin.php");
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $connection->prepare("DELETE FROM news WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: admin.php");
    exit;
}

$articles = $connection->query("SELECT id, title, published_date FROM news ORDER BY published_date DESC");
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <h1>Admin</h1>
    <p><a href="index.php">Till sidan</a> | <a href="index.php">Logga ut</a></p>

    <h2>Skapa ny artikel</h2>
    <?php if ($error): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="action" value="create">
        <label>Rubrik<br><input type="text" name="title" maxlength="255" required></label><br><br>
        <label>Ingress<br><textarea name="summary" rows="2" maxlength="500" required></textarea></label><br><br>
        <label>Brödtext<br><textarea name="content" rows="10" cols="60" required></textarea></label><br><br>
        <button type="submit">Publicera</button>
    </form>

    <h2>Publicerade artiklar</h2>
    <table border="1" cellpadding="6">
        <tr><th>Rubrik</th><th>Datum</th><th></th></tr>
        <?php while ($a = $articles->fetch_assoc()): ?>
            <tr>
                <td><a href="article.php?id=<?= (int)$a["id"] ?>"><?= htmlspecialchars($a["title"]) ?></a></td>
                <td><?= htmlspecialchars($a["published_date"]) ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Radera artikeln?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$a["id"] ?>">
                        <button type="submit">Radera</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $connection->close(); ?>