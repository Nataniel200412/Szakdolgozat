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

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    if (!in_array($id, $_SESSION['kosar'])) $_SESSION['kosar'][] = $id;
    header("Location: eszkozok.php");
    exit;
}

$termekek = $db->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eszközök</title>
    <link rel="stylesheet" href="css/global.css?v=2">
    <link rel="stylesheet" href="css/eszkozok.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="eszkozok.php" class="active">Eszközök</a></li>
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
<header><h1>Bérelhető Eszközeink</h1></header>
<div class="container">
    <div class="grid">
        <?php while ($gep = $termekek->fetch_assoc()): 
            $id = $gep['id'];
            $készlet = (int)$gep['stock'];
            $kategoria_kis = strtolower($gep['category']);
            $osztaly = 'tab-' . ($kategoria_kis === 'handheld' ? 'konzol' : ($kategoria_kis === 'kiegészítő' ? 'vr' : ($kategoria_kis === 'játékterem' ? 'arcade' : $kategoria_kis)));
        ?>
        <div class="card">
            <div class="card-category <?php echo $osztaly; ?>"><?php echo htmlspecialchars($gep['category']); ?></div>
            
            <div class="card-img-container">
                <img src="<?php echo htmlspecialchars($gep['image_url']); ?>" alt="<?php echo htmlspecialchars($gep['name']); ?>" <?php echo $készlet === 0 ? 'style="opacity: 0.3;"' : ''; ?>>
            </div>

            <h3><?php echo htmlspecialchars($gep['name']); ?></h3>
            <p><?php echo htmlspecialchars($gep['description']); ?></p>
            <div class="price"><?php echo number_format($gep['price_per_day'], 0, ',', ' '); ?> Ft / nap</div>
            
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <?php if ($készlet === 0): ?>
                    <button type="button" class="btn" disabled style="background: #1e293b; color: #64748b; border: 1px solid #334155; cursor: not-allowed; width: 100%;">❌ Átmenetileg elfogyott</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-add-cart" <?php echo in_array($id, $_SESSION['kosar']) ? 'disabled' : ''; ?>><?php echo in_array($id, $_SESSION['kosar']) ? '✓ Kosárban' : '🛒 Kosárba'; ?></button>
                <?php endif; ?>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
