<?php
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

if (isset($_POST['update_status'])) {
    $rental_id = (int)$_POST['rental_id'];
    $new_status = $_POST['status'];
    
    $get_rental = $db->prepare("SELECT product_id, status FROM rentals WHERE id = ?");
    $get_rental->bind_param("i", $rental_id);
    $get_rental->execute();
    $current_rental = $get_rental->get_result()->fetch_assoc();
    $get_rental->close();

    if ($current_rental && $current_rental['status'] !== $new_status) {
        $stmt = $db->prepare("UPDATE rentals SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $rental_id);
        $stmt->execute();
        $stmt->close();

        if ($new_status === 'Lezárt / Visszahozott' || $new_status === 'Lemondva') {
            if ($current_rental['status'] !== 'Lezárt / Visszahozott' && $current_rental['status'] !== 'Lemondva') {
                $update_stock = $db->prepare("UPDATE products SET stock = stock + 1 WHERE id = ?");
                $update_stock->bind_param("i", $current_rental['product_id']);
                $update_stock->execute();
                $update_stock->close();
            }
        }
    }
    
    header("Location: admin_berlesek.php");
    exit;
}

$orders = $db->query("SELECT r.*, u.name AS user_name, u.email AS user_email, p.name AS product_name FROM rentals r JOIN users u ON r.user_id = u.id JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bérlések Kezelése (Admin) - RentGamer</title>
    <link rel="stylesheet" href="css/global.css?v=2">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">RentGamer</a>
        <ul class="nav-menu">
            <li><a href="index.php">Kezdőlap</a></li>
            <li><a href="admin_berlesek.php" class="active">Bérlések kezelése</a></li>
            <li><a href="admin_feltoltes.php">Termék feltöltés</a></li>
        </ul>
        <div class="nav-auth">
            <span class="nav-user-name" style="color: #f59e0b; font-weight: bold;">🛠️ Adminisztrátor: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="logout.php" class="btn-logout">Kijelentkezés</a>
        </div>
    </div>
</nav>
<header><h1>Beérkezett Bérlési Igények</h1></header>
<div class="container">
    <?php if ($orders && $orders->num_rows > 0): ?>
        <?php while ($row = $orders->fetch_assoc()): ?>
            <div class="welcome-box" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="color: #38bdf8; margin-bottom: 5px;"><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    <p><strong>Bérlő:</strong> <?php echo htmlspecialchars($row['user_name']); ?> (<?php echo htmlspecialchars($row['user_email']); ?>)</p>
                    <p><strong>Időszak:</strong> <?php echo htmlspecialchars($row['start_date']); ?> — <?php echo htmlspecialchars($row['end_date']); ?></p>
                    <p><strong>Összeg:</strong> <?php echo number_format($row['total_price'], 0, ',', ' '); ?> Ft</p>
                    <p><strong>Jelenlegi státusz:</strong> <span style="color: #4ade80;"><?php echo htmlspecialchars($row['status']); ?></span></p>
                </div>
                <div>
                    <form action="" method="POST">
                        <input type="hidden" name="rental_id" value="<?php echo $row['id']; ?>">
                        <select name="status" style="padding: 10px; background: #1e293b; color: white; border: 1px solid #334155; border-radius: 5px; margin-right: 10px;">
                            <option value="Feldolgozás alatt" <?php echo $row['status'] === 'Feldolgozás alatt' ? 'selected' : ''; ?>>Feldolgozás alatt</option>
                            <option value="Átvehető" <?php echo $row['status'] === 'Átvehető' ? 'selected' : ''; ?>>Átvehető</option>
                            <option value="Nálad van" <?php echo $row['status'] === 'Nálad van' ? 'selected' : ''; ?>>Nálad van</option>
                            <option value="Lezárt / Visszahozott" <?php echo $row['status'] === 'Lezárt / Visszahozott' ? 'selected' : ''; ?>>Lezárt / Visszahozott</option>
                            <option value="Lemondva" <?php echo $row['status'] === 'Lemondva' ? 'selected' : ''; ?>>Lemondva</option>
                        </select>
                        <button type="submit" name="update_status" class="btn">Módosít</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: #94a3b8; text-align: center;">Még nem érkezett bérlési igény a rendszerbe.</p>
    <?php endif; ?>
</div>
</body>
</html>
