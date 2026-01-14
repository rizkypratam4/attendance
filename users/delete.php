<?php
require __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID tidak dikirim'
    ]);
    exit;
}

$id = (int) $_POST['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'User berhasil dihapus'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menghapus user'
    ]);
}

$stmt->close();
