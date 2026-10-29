<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'register') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (!empty($name) && !empty($email) && !empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: login.php?success=1");
                exit;
            } else {
                $stmt->close();
                header("Location: register.php?error=email_taken");
                exit;
            }
        } else {
            header("Location: register.php?error=empty_fields");
            exit;
        }
    }

    if ($action === 'login') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (!empty($email) && !empty($password)) {
            $stmt = $db->prepare("SELECT id, name, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'];
                    $stmt->close();
                    header("Location: index.php");
                    exit;
                }
            }
            $stmt->close();
            header("Location: login.php?error=invalid_credentials");
            exit;
        }
    }
}
