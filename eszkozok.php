<?php
session_start();
if (!isset($_SESSION['kosar'])) $_SESSION['kosar'] = [];
$gepek = [
    1 => ['nev' => 'PlayStation 5 Slim', 'leiras' => '1TB tárhely, 2db kontroller.', 'ar' => 5000, 'osztaly' => 'tab-konzol', 'cimke' => 'Konzol'],
    2 => ['nev' => 'Xbox Series X', 'leiras' => '4K gaming, 2db kontroller.', 'ar' => 5000, 'osztaly' => 'tab-konzol', 'cimke' => 'Konzol'],
    3 => ['nev' => 'Nintendo Switch OLED', 'leiras' => 'Hordozható konzol prémium kijelzővel.', 'ar' => 4000, 'osztaly' => 'tab-konzol', 'cimke' => 'Konzol'],
    4 => ['nev' => 'Meta Quest 3 VR', 'leiras' => '128GB-os önálló VR szemüveg.', 'ar' => 7000, 'osztaly' => 'tab-vr', 'cimke' => 'VR Szemüveg'],
    5 => ['nev' => 'PlayStation VR2', 'leiras' => 'PS5-höz csatlakoztatható VR szemüveg.', 'ar' => 6500, 'osztaly' => 'tab-vr', 'cimke' => 'VR Szemüveg'],
    6 => ['nev' => 'Retro Játéktermi Gép', 'leiras' => 'Arcade kabinet 2 fő részére.', 'ar' => 12000, 'osztaly' => 'tab-arcade', 'cimke' => 'Játékterem']
];
if (isset($_POST['id'])) {
    if (!in_array($_POST['id'], $_SESSION['kosar'])) $_SESSION['kosar'][] = (int)$_POST['id'];
    header("Location: eszkozok.php" . (isset($_GET['belepve']) ? "?belepve=1" : ""));
    exit;
}
$belepve = isset($_GET['belepve']) && $_GET['belepve'] == '1';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eszközök</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/eszkozok.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Kezdőlap</a></li>
            <li><a href="eszkozok.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="active">Eszközök</a></li>
            <li><a href="feltetelek.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Bérlési feltételek</a></li>
            <li><a href="kapcsolat.php<?php echo $belepve ? '?belepve=1' : ''; ?>">Kapcsolat</a></li>
        </ul>
        <div class="nav-oldal">
            <a href="kosar.php<?php echo $belepve ? '?belepve=1' : ''; ?>" class="btn-login">🛒 Kosár (<?php echo count($_SESSION['kosar']); ?>)</a>
        </div>
    </div>
</nav>
<header><h1>Bérelhető Eszközeink</h1></header>
<div class="container">
    <div class="grid" style="margin-top: 20px;">
        <?php foreach ($gepek as $id => $gep): ?>
        <div class="card">
            <div class="card-category <?php echo $gep['osztaly']; ?>"><?php echo $gep['cimke']; ?></div>
            <h3 style="margin-top:20px;"><?php echo $gep['nev']; ?></h3>
            <p><?php echo $gep['leiras']; ?></p>
            <div class="price"><?php echo $gep['ar']; ?> Ft / nap</div>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" class="btn btn-add-cart" <?php echo in_array($id, $_SESSION['kosar']) ? 'disabled style="background:#334155;color:#94a3b8;cursor:not-allowed;"' : ''; ?>><?php echo in_array($id, $_SESSION['kosar']) ? '✓ Kosárban' : '🛒 Kosárba'; ?></button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>