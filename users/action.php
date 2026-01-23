<?php

require __DIR__ . "/../config/database.php";

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
    exit;
}

$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $username === '' || $password === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua field harus diisi!'
    ]);
    exit;
}

if (strlen($username) < 6) {
    echo json_encode([
        'status' => 'error',
        'message' => "Username harus terdiri dari 6 karakter"
    ]);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username sudah digunakan!'
    ]);
    exit;
}
$stmt->close();

$stmt = $conn->prepare(
    "INSERT INTO users (name, username, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $username, $passwordHash);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'User baru berhasil ditambahkan'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan user'
    ]);
}

$stmt->close();
exit;
