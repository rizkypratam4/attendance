<?php
session_start();
require __DIR__ . "/../config/database.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param('s', $username);
$query->execute();

$result = $query->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['name'] = $user['name'];
    header("Location: ../dashboard/");
} else {
    $_SESSION['error'] = "Username atau password salah";
    header("Location: ../");
}