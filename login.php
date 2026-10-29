<?php
require_once 'db.php';
if (!isset($_SESSION['kosar'])) $_SESSION['kosar'] = [];
$darab = count($_SESSION['kosar']);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Belépés</title>
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
            <a href="kosar.php" class="btn-cart">🛒 Kosár (<?php echo $darab; ?>)</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="nav-user-name">Szia, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="logout.php" class="btn-login btn-logout">Kijelentkezés</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Bejelentkezés</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container">
    <div class="auth-container">
        <div class="auth-box">
            <h2 style="color:#38bdf8; margin-top:0;">Bejelentkezés</h2>
            
            <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
                <div class="alert-error">Hibás e-mail cím vagy jelszó!</div>
            <?php endif; ?>

            <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
                <div class="alert-success">Sikeres regisztráció! Most már bejelentkezhetsz.</div>
            <?php endif; ?>

            <form action="auth_action.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="form-group"><input type="email" name="email" required placeholder="E-mail"></div>
                <div class="form-group"><input type="password" name="password" required placeholder="Jelszó"></div>
                <button type="submit" class="btn auth-btn">Belépés</button>
            </form>
            <p class="auth-switch"><a href="register.php">Regisztráció</a></p>
        </div>
    </div>
</div>
</body>
</html>
