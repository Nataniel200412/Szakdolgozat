<?php
session_start();
if (!isset($_SESSION['kosar'])) $_SESSION['kosar'] = [];
$belepve = isset($_GET['belepve']) && $_GET['belepve'] == '1';
$darab = count($_SESSION['kosar']);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentGamer - Kezdőlap</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="active">Kezdőlap</a></li>
            <li><a href="eszkozok.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Eszközök</a></li>
            <li><a href="feltetelek.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Bérlési feltételek</a></li>
            <li><a href="kapcsolat.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Kapcsolat</a></li>
        </ul>
        <div class="nav-auth">
            <a href="kosar.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="btn-cart" style="margin-right: 15px; text-decoration: none;">🛒 Kosár (<?php echo $darab; ?>)</a>
            <?php if ($belepve): ?>
                <span style="color: #cbd5e1; margin-right: 15px;">Üdv, Felhasználó!</span>
                <a href="index.php" class="btn-login" style="border-color: #ef4444; color: #ef4444;">Kijelentkezés</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Bejelentkezés</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<header>
    <h1>Szórakoztatóelektronikai Eszközök Bérlése</h1>
    <p>Játékkonzolok és VR szemüvegek online rendszere</p>
</header>
<div class="container">
    <div class="welcome-box">
        <h2>Miért válassz minket?</h2>
        <p>Nálunk egyszerűen, gyorsan és biztonságosan bérelhetsz prémium eszközöket.</p>
    </div>
    <?php if ($belepve): ?>
        <h2>Saját bérléseim</h2>
        <div class="rental-status-card">
            <div class="rental-header">
                <h3>Meta Quest 3 VR</h3>
                <span class="badge active-badge">Nálad van</span>
            </div>
            <div class="rental-details">
                <p>Határidő: 2026. október 05.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>