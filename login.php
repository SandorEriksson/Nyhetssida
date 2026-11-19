<?php
    if(isset($_POST["username"]) && isset($_POST["password"])){
        require("dbConnect.php");
        $stmt = $connection->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $_POST["username"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $admin = $result->fetch_assoc();
            if($_POST["password"] == $admin["password"]){
                session_start();
                $_SESSION["admin_id"] = $admin["id"];
                header("Location: admin.php");
            } 
        } 

        $error = "Fel användarnamn eller lösenord.";
    }
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Logga in</title>
</head>
<body>
    <h1>Admin-inloggning</h1>
    <?php 
    if(isset($error)){
        echo '<p style="color:red">'.htmlspecialchars($error).'</p>';
    }
    ?>
    <form method="post">
        <label>Användarnamn <input type="text" name="username" required></label><br>
        <label>Lösenord <input type="password" name="password" required></label><br>
        <button type="submit">Logga in</button>
    </form>
    <a href="index.php">&larr; Tillbaka</a>
</body>
</html>
