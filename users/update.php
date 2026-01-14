<?php
require __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$id = $_POST['id'];
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$password = $_POST['password'] ?? '';

if (!$id || !$name || !$username) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

// cek username dipakai user lain
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
$stmt->bind_param("si", $username, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username sudah digunakan']);
    exit;
}
$stmt->close();

if (!empty($password)) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET name=?, username=?, password=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $username, $hash, $id);
} else {
    $stmt = $conn->prepare("UPDATE users SET name=?, username=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $username, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'User berhasil diperbarui']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal update user']);
}

$stmt->close();
