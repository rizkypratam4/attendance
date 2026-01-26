<?php
header('Content-Type: application/json');
require __DIR__ . "/../config/database.php";

$barcode = $_GET['barcode'] ?? null;

if (!$barcode) {
  echo json_encode([
    'status' => 'error',
    'message' => 'ID tidak ditemukan'
  ]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT *
  FROM employees
  WHERE barcode = ?
");
$stmt->execute([$barcode]);

$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
  echo json_encode([
    'status' => 'error',
    'message' => 'Employee tidak ditemukan'
  ]);
  exit;
}

$dateFields = [
  'date_of_birth',
  'join_date',
  'effective_date'
];

foreach ($dateFields as $field) {
  if (!empty($employee[$field])) {
    $employee[$field] = date('Y-m-d', strtotime($employee[$field]));
  }
}

echo json_encode([
  'status' => 'success',
  'data' => $employee
]);
?>