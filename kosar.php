<?php
require_once 'db.php';

if (!isset($_SESSION['kosar'])) $_SESSION['kosar'] = [];
$darab = count($_SESSION['kosar']);

if (isset($_POST['torol'])) {
    $id = (int)$_POST['torol'];
    if (($k = array_search($id, $_SESSION['kosar'])) !== false) {
        unset($_SESSION['kosar'][$k]);
    }
    $_SESSION['kosar'] = array_values($_SESSION['kosar']);
    header("Location: kosar.php");
    exit;
}

$kosar_elemek = [];
$napidij = 0;
$kaucio = 0;

if (!empty($_SESSION['kosar'])) {
    $ids = implode(',', array_map('intval', $_SESSION['kosar']));
    $result = $db->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $kosar_elemek[$row['id']] = $row;
        $napidij += $row['price_per_day'];
        $kaucio += ($row['price_per_day'] * 4);
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'checkout') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!empty($start_date) && !empty($end_date) && !empty($_SESSION['kosar'])) {
        $d1 = new DateTime($start_date);
        $d2 = new DateTime($end_date);
        $diff = $d1->diff($d2);
        $napok = (int)$diff->format('%r%a');

        if ($napok > 0) {
            $total_price = $napok * $napidij;
            $user_id = $_SESSION['user_id'];

            foreach ($_SESSION['kosar'] as $product_id) {
                $stmt = $db->prepare("INSERT INTO rentals (user_id, product_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iissi", $user_id, $product_id, $start_date, $end_date, $total_price);
                $stmt->execute();
                $stmt->close();

                $update_stock = $db->prepare("UPDATE products SET stock = stock - 1 WHERE id = ? AND stock > 0");
                $update_stock->bind_param("i", $product_id);
                $update_stock->execute();
                $update_stock->close();
            }

            $_SESSION['kosar'] = [];
            header("Location: kosar.php?success=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kosár</title>
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
            <li><a href="kapcsolat.php">Kapcsolat</a></li>
        </ul>
        <div class="nav-auth">
            <?php if (isset($_SESSION['user_id'])): 
                $check_nav = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
                $check_nav->bind_param("i", $_SESSION['user_id']);
                $check_nav->execute();
                $nav_res = $check_nav->get_result()->fetch_assoc();
                $check_nav->close();
                if ($nav_res && (int)$nav_res['is_admin'] === 1): ?>
                    <a href="admin_berlesek.php" class="btn-cart" style="color: #f59e0b; font-weight: bold;">🛠️ Admin Panel</a>
                <?php endif; 
            endif; ?>
            <a href="kosar.php" class="btn-cart">🛒 Kosár (<?php echo $darab; ?>)</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="nav-user-name">Szia, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="logout.php" class="btn-logout">Kijelentkezés</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Bejelentkezés</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<header><h1>Bérlési Kosár</h1></header>
<div class="container">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert-success">Bérlési igényedet sikeresen rögzítettük rendszerünkben!</div>
    <?php endif; ?>

    <div class="welcome-box" style="display:grid; grid-template-columns: 1fr 1fr; gap:30px;">
        <div>
            <h3 style="color:#38bdf8; margin-top:0; border-bottom:1px solid #334155; padding-bottom:10px;">Kiválasztott eszközök</h3>
            <?php foreach ($_SESSION['kosar'] as $id): if (isset($kosar_elemek[$id])): $item = $kosar_elemek[$id]; ?>
                <div class="rental-status-card" style="display:flex; justify-content:space-between; align-items:center; background:#0f172a; margin-bottom:10px; border:1px solid #334155; padding: 15px; border-radius: 5px;">
                    <div>
                        <h4 style="margin:0; color:white;"><?php echo htmlspecialchars($item['name']); ?></h4>
                        <span style="font-size:0.85rem; color:#94a3b8;">Díj: <?php echo number_format($item['price_per_day'], 0, ',', ' '); ?> Ft/nap | Kaució: <?php echo number_format($item['price_per_day'] * 4, 0, ',', ' '); ?> Ft</span>
                    </div>
                    <form action="" method="POST" style="margin:0;">
                        <input type="hidden" name="torol" value="<?php echo $id; ?>">
                        <button type="submit" style="color:#ef4444; background:none; border:none; cursor:pointer; font-weight:bold;">Törlés</button>
                    </form>
                </div>
            <?php endif; endforeach; if(empty($_SESSION['kosar'])) echo "<p style='color:#94a3b8;'>A kosarad jelenleg üres.</p>"; ?>
        </div>
        
        <div style="background:#0f172a; padding:25px; border-radius:8px; border:1px solid #334155;">
            <h3 style="color:#38bdf8; margin-top:0;">Bérlési időszak</h3>
            <form action="" method="POST">
                <input type="hidden" name="action" value="checkout">
                <div style="display:flex; gap:15px; margin-bottom:20px;">
                    <div style="width:50%;"><label style="font-size:0.9rem; color:#cbd5e1;">Kezdete</label><input type="date" name="start_date" id="mettol" required style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box;"></div>
                    <div style="width:50%;"><label style="font-size:0.9rem; color:#cbd5e1;">Vége</label><input type="date" name="end_date" id="meddig" required style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box;"></div>
                </div>
                <p>Napi díj összesen: <strong style="color:#4ade80;"><?php echo number_format($napidij, 0, ',', ' '); ?> Ft</strong></p>
                <p>Kaució összesen: <strong style="color:#f59e0b;"><?php echo number_format($kaucio, 0, ',', ' '); ?> Ft</strong></p>
                <p>Fizetendő végösszeg: <strong id="vege" style="color:#4ade80;">-- Ft</strong></p>
                
                <?php if (empty($_SESSION['kosar'])): ?>
                    <button type="button" class="btn" disabled style="opacity:0.5;cursor:not-allowed; width: 100%;">A kosár üres</button>
                <?php elseif (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="btn" style="display: block; text-align: center; text-decoration: none; width: 100%; box-sizing: border-box; background: #f59e0b; color: #fff;">Jelentkezz be a bérléshez</a>
                <?php else: ?>
                    <button type="submit" class="btn" style="width: 100%;">Igény leadása</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
<script>
    const m = document.getElementById('mettol'), g = document.getElementById('meddig'), v = document.getElementById('vege');
    function szamol() {
        if(m.value && g.value) {
            const d1 = new Date(m.value), d2 = new Date(g.value);
            const n = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
            if(n > 0) { v.innerText = (n * <?php echo $napidij; ?>).toLocaleString('hu-HU') + ' Ft'; } else { v.innerText = '-- Ft'; }
        }
    }
    m.addEventListener('change', szamol); g.addEventListener('change', szamol);
</script>
</body>
</html>
