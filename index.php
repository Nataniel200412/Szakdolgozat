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

    $stmt = $db->prepare("SELECT r.*, p.name AS termek_nev FROM rentals r JOIN products p ON r.product_id = p.id WHERE r.user_id = ? ORDER BY r.created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $rental_result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentGamer - Kezdőlap</title>
    <link rel="stylesheet" href="css/global.css?v=2">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php" class="active">Kezdőlap</a></li>
            <li><a href="eszkozok.php">Eszközök</a></li>
            <li><a href="feltetelek.php">Bérlési feltételek</a></li>
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
    <h1>Szórakoztatóelektronikai Eszközök Bérlése</h1>
    <p>Játékkonzolok és VR szemüvegek online rendszere</p>
</header>
<div class="container">
    <div class="welcome-box">
        <h2>Miért válassz minket?</h2>
        <p>Nálunk egyszerűen, gyorsan és biztonságosan bérelhetsz prémium eszközöket.</p>
    </div>
    
    <?php if ($belepve): ?>
        <h2 class="rental-list-title">Saját bérléseim</h2>
        <?php if ($rental_result && $rental_result->num_rows > 0): ?>
            <?php while ($berles = $rental_result->fetch_assoc()): ?>
                <div class="rental-status-card">
                    <div class="rental-header">
                        <h3 class="rental-card-title"><?php echo htmlspecialchars($berles['termek_nev']); ?></h3>
                        <span class="rental-badge"><?php echo htmlspecialchars($berles['status']); ?></span>
                    </div>
                    <div class="rental-card-details">
                        <p><strong>Időszak:</strong> <?php echo htmlspecialchars($berles['start_date']); ?> — <?php echo htmlspecialchars($berles['end_date']); ?></p>
                        <p><strong>Teljes bérleti díj:</strong> <span class="rental-total-highlight"><?php echo number_format($berles['total_price'], 0, ',', ' '); ?> Ft</span></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="rental-card-details">Még nem adtál le bérlési igényt.</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>
    <?php endif; ?>
</div>
</body>
</html>
