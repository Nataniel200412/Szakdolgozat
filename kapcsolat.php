<?php
require_once 'db.php';

if (!isset($_SESSION['kosar'])) $_SESSION['kosar'] = [];
$darab = count($_SESSION['kosar']);

$belepve = isset($_SESSION['user_id']);
$is_admin_user = false;

if ($belepve) {
    $user_id = $_SESSION['user_id'];
    $check_admin = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
    $check_admin->bind_param("i", $user_id);
    $check_admin->execute();
    $admin_res = $check_admin->get_result()->fetch_assoc();
    $check_admin->close();
    if ($admin_res && (int)$admin_res['is_admin'] === 1) {
        $is_admin_user = true;
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kapcsolat - RentGamer</title>
    <link rel="stylesheet" href="css/global.css?v=2">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="eszkozok.php">Eszközök</a></li>
            <li><a href="feltetelek.php">Bérlési feltételek</a></li>
            <li><a href="kapcsolat.php" class="active">Kapcsolat</a></li>
        </ul>
        <div class="nav-auth">
            <?php if ($is_admin_user): ?>
                <a href="admin_berlesek.php" class="btn-cart" style="color: #f59e0b; font-weight: bold;">🛠️ Admin Panel</a>
            <?php endif; ?>
            <a href="kosar.php" class="btn-cart">🛒 Kosár (<?php echo $darab; ?>)</a>
            <?php if ($belepve): ?>
                <?php if ($is_admin_user): ?>
                    <span class="nav-user-name" style="color: #f59e0b; font-weight: bold;">🛠️ Admin: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <?php else: ?>
                    <span class="nav-user-name">Szia, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <?php endif; ?>
                <a href="logout.php" class="btn-logout">Kijelentkezés</a>
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
                    <input type="text" required placeholder="Teszt Elek" value="<?php echo $belepve ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box;">
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
