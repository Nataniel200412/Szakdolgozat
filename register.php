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
            <?php if (isset($_SESSION['user_id'])): ?>
                <span style="color: #fff; margin-right: 15px;">Szia, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="logout.php" class="btn-login" style="background: #ef4444;">Kijelentkezés</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Bejelentkezés</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container">
    <div class="auth-container">
        <div class="auth-box">
            <h2 style="color:#38bdf8; margin-top:0;">Regisztráció</h2>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'email_taken'): ?>
                <div style="color: #ef4444; background: #2d1a1a; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; border: 1px solid #ef4444;">
                    Ez az e-mail cím már regisztrálva van!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'empty_fields'): ?>
                <div style="color: #ef4444; background: #2d1a1a; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; border: 1px solid #ef4444;">
                    Kérjük, töltsön ki minden mezőt!
                </div>
            <?php endif; ?>

            <form action="auth_action.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-group"><input type="text" name="name" required placeholder="Név"></div>
                <div class="form-group"><input type="email" name="email" required placeholder="E-mail"></div>
                <div class="form-group"><input type="password" name="password" required placeholder="Jelszó"></div>
                <button type="submit" class="btn auth-btn">Fiók létrehozása</button>
            </form>
            <p class="auth-switch"><a href="login.php">Már van fiókom? Belépek</a></p>
        </div>
    </div>
</div>
</body>
</html>