<?php
session_start();
if (!isset($_SESSION['kosar'])) $_SESSION['kosar'] = [];
$darab = count($_SESSION['kosar']);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="eszkozok.php">Eszközök</a></li>
            <li><a href="feltetelek.php">Bérlési feltételek</a></li>
            <li><a href="kapcsolat.php">Kapcsolat</a></li>
        </ul>
        <div class="nav-auth">
            <a href="kosar.php" class="btn-cart" style="margin-right: 15px; text-decoration: none;">🛒 Kosár (<?php echo $darab; ?>)</a>
            <a href="login.php" class="btn-login">Bejelentkezés</a>
        </div>
    </div>
</nav>
<div class="container">
    <div class="auth-container">
        <div class="auth-box">
            <h2 style="color:#38bdf8; margin-top:0;">Regisztráció</h2>
            <form action="login.php" method="POST">
                <div class="form-group"><input type="text" required placeholder="Név"></div>
                <div class="form-group"><input type="email" required placeholder="E-mail"></div>
                <div class="form-group"><input type="password" required placeholder="Jelszó"></div>
                <button type="submit" class="btn auth-btn">Fiók létrehozása</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
