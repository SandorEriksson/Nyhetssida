<?php
session_start();
require("dbConnect.php");
$data = $connection->query("SELECT id, title, summary, published_date FROM news ORDER BY published_date DESC");
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nyhetssida</title>
</head>
<body>
    <header>
        <h1>Nyhetssida</h1>
        <p>Välkommen till vårt nyhetsutbud!</p>
        <nav>
            <a href="index.php">Nyheter</a>
            <?php if (!empty($_SESSION["admin_id"])): ?>
                <a href="admin.php">Admin</a>
             
            <?php else: ?>
                <a href="login.php">Logga in</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php while ($row = $data->fetch_assoc()): ?>
            <section class="news">
                <h3>
                    <a href="article.php?id=<?= (int)$row["id"] ?>">
                        <?= htmlspecialchars($row["title"]) ?>
                    </a>
                </h3>
                <p><?= htmlspecialchars($row["summary"]) ?></p>
                <p>Publicerad: <?= htmlspecialchars($row["published_date"]) ?></p>
            </section>
        <?php endwhile; ?>
    </main>
</body>
</html>
<?php $connection->close(); ?>