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
    <title>Bérlési Feltételek - RentGamer</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Kezdőlap</a></li>
            <li><a href="eszkozok.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Eszközök</a></li>
            <li><a href="feltetelek.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="active">Bérlési feltételek</a></li>
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
    <h1>Bérlési Feltételek</h1>
</header>
<div class="container">
    <div class="welcome-box">
        <div style="border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #38bdf8; margin-top: 0;">1. Általános szabályok</h3>
            <p style="color: #cbd5e1;">A bérléshez érvényes regisztráció, betöltött 18. életév és fényképes igazolvány bemutatása szükséges az eszközök átvételekor.</p>
        </div>
        <div style="border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #38bdf8; margin-top: 0;">2. Kaució és fizetés</h3>
            <p style="color: #cbd5e1;">Minden eszköz bérlése esetén kaució (letét) fizetése kötelező, amelyet a rendszer automatikusan összesít. Az eszközök sérülésmentes visszahozatala után a teljes kaució azonnal visszajár.</p>
        </div>
        <div style="border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #38bdf8; margin-top: 0;">3. Bérleti idő és késedelem</h3>
            <p style="color: #cbd5e1;">A minimális bérlési időtartam 1 nap (24 óra). Késedelmes leadás esetén a rendszer automatikusan felszámítja a következő napi bérleti díjat pótdíjként.</p>
        </div>
        <div>
            <h3 style="color: #38bdf8; margin-top: 0;">4. Károk és felelősség</h3>
            <p style="color: #cbd5e1;">A bérlő teljes anyagi felelősséggel tartozik a bérelt gépekért és tartozékokért. Rongálás, törés vagy hiányos tartozékok esetén a kár értéke a kaucióból kerül levonásra.</p>
        </div>
        <a href="index.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="btn" style="margin-top: 25px; display: inline-block; text-decoration: none;">Vissza a főoldalra</a>
    </div>
</div>
</body>
</html>
