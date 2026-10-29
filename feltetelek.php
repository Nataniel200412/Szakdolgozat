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
    <title>Bérlési Feltételek - RentGamer</title>
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
            <li><a href="feltetelek.php" class="active">Bérlési feltételek</a></li>
            <li><a href="kapcsolat.php">Kapcsolat</a></li>
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
            <p style="color: #cbd5e1;">Minden eszköz bérlése esetén kaució (letét) fizetése kötelező, amelyet a system automatikusan összesít. Az eszközök sérülésmentes visszahozatala után a teljes kaució azonnal visszajár.</p>
        </div>
        <div style="border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 style="color: #38bdf8; margin-top: 0;">3. Bérleti idő és késedelem</h3>
            <p style="color: #cbd5e1;">A minimális bérlési időtartam 1 nap (24 óra). Késedelmes leadás esetén a system automatikusan felszámítja a következő napi bérleti díjat pótdíjként.</p>
        </div>
        <div>
            <h3 style="color: #38bdf8; margin-top: 0;">4. Károk és felelősség</h3>
            <p style="color: #cbd5e1;">A bérlő teljes anyagi felelősséggel tartozik a bérelt gépekért és tartozékokért. Rongálás, törés vagy hiányos tartozékok esetén a kár értéke a kaucióból kerül levonásra.</p>
        </div>
        <a href="index.php" class="btn" style="margin-top: 25px; display: inline-block; text-decoration: none;">Vissza a főoldalra</a>
    </div>
</div>
</body>
</html>
