<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$check_admin = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
$check_admin->bind_param("i", $_SESSION['user_id']);
$check_admin->execute();
$admin_res = $check_admin->get_result()->fetch_assoc();
$check_admin->close();

if (!$admin_res || (int)$admin_res['is_admin'] !== 1) {
    header("Location: index.php");
    exit;
}

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price_per_day = (int)$_POST['price_per_day'];
    $image_url = trim($_POST['image_url']);
    $stock = (int)$_POST['stock'];

    if (!empty($name) && !empty($category) && $price_per_day > 0) {
        $stmt = $db->prepare("INSERT INTO products (name, category, description, price_per_day, image_url, stock) VALUES (?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            die("SQL hiba: " . $db->error);
        }
        
        $stmt->bind_param("sssisi", $name, $category, $description, $price_per_day, $image_url, $stock);
        
        if ($stmt->execute()) {
            $success_message = 'Termék sikeresen hozzáadva!';
        } else {
            die("SQL futtatási hiba: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Hiba: Hiányzó vagy hibás mezők!");
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Termék Feltöltés (Admin) - RentGamer</title>
    <link rel="stylesheet" href="css/global.css?v=2">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="admin_berlesek.php">Bérlések kezelése</a></li>
            <li><a href="admin_feltoltes.php" class="active">Termék feltöltés</a></li>
        </ul>
        <div class="nav-auth">
            <span class="nav-user-name" style="color: #f59e0b; font-weight: bold;">🛠️ Adminisztrátor: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="logout.php" class="btn-logout">Kijelentkezés</a>
        </div>
    </div>
</nav>
<header>
    <h1>Termékkezelés</h1>
    <p>Új bérhető eszközök rögzítése a rendszerben</p>
</header>
<div class="container" style="margin-top: 50px;">
    <div class="auth-container">
        <div class="auth-box" style="max-width: 500px;">
            <h2 style="color:#38bdf8; margin-top:0; text-align: center;">Új eszköz hozzáadása</h2>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group"><input type="text" name="name" required placeholder="Eszköz neve (pl. Xbox Series S)"></div>
                <div class="form-group"><input type="text" name="category" required placeholder="Kategória (Konzol / VR Szemüveg / Játékterem)"></div>
                <div class="form-group"><textarea name="description" placeholder="Rövid leírás..." style="width:100%; height:80px; background:#1e293b; border:1px solid #334155; color:white; padding:10px; border-radius:5px; box-sizing:border-box; font-family: sans-serif;"></textarea></div>
                <div class="form-group"><input type="number" name="price_per_day" required placeholder="Napi bérleti díj (Ft)"></div>
                <div class="form-group"><input type="text" name="image_url" required placeholder="Kép elérése (pl. images/xbox.jpg)"></div>
                <div class="form-group"><input type="number" name="stock" required placeholder="Készlet darabszám" value="1"></div>
                <button type="submit" class="btn auth-btn">Termék mentése</button>
            </form>
            <p class="auth-switch" style="text-align: center;"><a href="eszkozok.php">Vissza az eszközökhöz</a></p>
        </div>
    </div>
</div>
</body>
</html>
