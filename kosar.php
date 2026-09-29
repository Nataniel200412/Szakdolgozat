<?php
session_start();
if (!isset($_SESSION['kosar'])) $_SESSION['kosar'] = [];
$gepek = [
    1 => ['nev'=>'PlayStation 5 Slim','ar'=>5000,'kaucio'=>20000], 
    2 => ['nev'=>'Xbox Series X','ar'=>5000,'kaucio'=>20000], 
    3 => ['nev'=>'Nintendo Switch OLED','ar'=>4000,'kaucio'=>10000], 
    4 => ['nev'=>'Meta Quest 3 VR','ar'=>7000,'kaucio'=>30000], 
    5 => ['nev'=>'PlayStation VR2','ar'=>6500,'kaucio'=>25000], 
    6 => ['nev'=>'Retro Játéktermi Gép','ar'=>12000,'kaucio'=>50000]
];
if (isset($_POST['torol'])) {
    if (($k = array_search($_POST['torol'], $_SESSION['kosar'])) !== false) unset($_SESSION['kosar'][$k]);
    $_SESSION['kosar'] = array_values($_SESSION['kosar']);
    header("Location: kosar.php" . (isset($_GET['belepve']) ? "?belepve=1" : ""));
    exit;
}
$belepve = isset($_GET['belepve']) && $_GET['belepve'] == '1';
$napidij = 0; $kaucio = 0;
foreach ($_SESSION['kosar'] as $id) {
    if (isset($gepek[$id])) { $napidij += $gepek[$id]['ar']; $kaucio += $gepek[$id]['kaucio']; }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kosár</title>
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
            <li><a href="kapcsolat.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Kapcsolat</a></li>
        </ul>
    </div>
</nav>
<header><h1>Bérlési Kosár</h1></header>
<div class="container">
    <div class="welcome-box" style="display:grid; grid-template-columns: 1fr 1fr; gap:30px;">
        <div>
            <h3 style="color:#38bdf8; margin-top:0; border-bottom:1px solid #334155; padding-bottom:10px;">Kiválasztott eszközök</h3>
            <?php foreach ($_SESSION['kosar'] as $id): if (isset($gepek[$id])): ?>
                <div class="rental-status-card" style="display:flex; justify-content:space-between; align-items:center; background:#0f172a; margin-bottom:10px; border:1px solid #334155;">
                    <div>
                        <h4 style="margin:0; color:white;"><?php echo $gepek[$id]['nev']; ?></h4>
                        <span style="font-size:0.85rem; color:#94a3b8;">Díj: <?php echo $gepek[$id]['ar']; ?> Ft | Kaució: <?php echo $gepek[$id]['kaucio']; ?> Ft</span>
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
            <div style="display:flex; gap:15px; margin-bottom:20px;">
                <div style="width:50%;"><label style="font-size:0.9rem; color:#cbd5e1;">Kezdete</label><input type="date" id="mettol" style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box;"></div>
                <div style="width:50%;"><label style="font-size:0.9rem; color:#cbd5e1;">Vége</label><input type="date" id="meddig" style="width:100%; padding:10px; background:#1e293b; border:1px solid #334155; color:white; border-radius:5px; box-sizing:border-box;"></div>
            </div>
            <p>Napi díj összesen: <strong style="color:#4ade80;"><?php echo $napidij; ?> Ft</strong></p>
            <p>Kaució összesen: <strong style="color:#f59e0b;"><?php echo $kaucio; ?> Ft</strong></p>
            <p>Fizetendő végösszeg: <strong id="vege" style="color:#4ade80;">-- Ft</strong></p>
            <button class="btn" <?php echo empty($_SESSION['kosar']) ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''; ?> onclick="alert('Igény elküldve!')">Igény leadása</button>
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