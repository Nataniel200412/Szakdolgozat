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
    <title>Kapcsolat - RentGamer</title>
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
            <li><a href="feltetelek.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Bérlési feltételek</a></li>
            <li><a href="kapcsolat.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="active">Kapcsolat</a></li>
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
    <h1>Kapcsolat</h1>
</header>
<div class="container">
    <div class="welcome-box" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; padding: 30px;">
        <div>
            <h3 style="color: #38bdf8; margin-top: 0;">Átvételi Pont & Információk</h3>
            <p style="color: #cbd5e1; margin-bottom: 20px;">Az eszközök személyesen vehetők át és tesztelhetők a központi raktárunkban.</p>
            <p><strong>📍 Cím:</strong> 9999 Tesztváros, Pixel utca 404.</p>
            <p><strong>📞 Telefon:</strong> +36 99 999 9999</p>
            <p><strong>✉️ E-mail:</strong> kapcsolat@rentgamertest.hu</p>
            <p><strong>⏰ Nyitvatartás:</strong> Hétfő - Péntek: 10:00 - 18:00</p>
        </div>
        <div style="background-color: #0f172a; padding: 20px; border-radius: 8px; border: 1px solid #334155;">
            <h3 style="color: #38bdf8; margin-top: 0; margin-bottom: 15px;">Írj nekünk üzenetet</h3>
            <form action="#" method="POST" onsubmit="alert('Köszönjük, az üzenetet rögzítettük!'); return false;">
                <div style="margin-bottom: 15px;">
                    <label style="display:block; color:#cbd5e1; margin-bottom:5px; font-size:0.9rem;">Neved</label>
                    <input type="text" required placeholder="Teszt Elek" style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; color:#cbd5e1; margin-bottom:5px; font-size:0.9rem;">E-mail címed</label>
                    <input type="email" required placeholder="teszt@rentgamertest.hu" style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; color:#cbd5e1; margin-bottom:5px; font-size:0.9rem;">Üzenet</label>
                    <textarea required placeholder="Ide írd a kérdésedet..." rows="4" style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box; font-family:sans-serif;"></textarea>
                </div>
                <button type="submit" class="btn" style="width:100%; padding:12px; border:none; cursor:pointer;">Üzenet elküldése</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
