<?php
session_start();
require __DIR__ . "../../function.php";
$conn = openConnectionMSSQL();

if (isset($_POST['import'])) {
    try {
        importExcel(
            'dbo.karyawan',
            [
                'employee_name' => 1,
                'nik' => 2,
                'barcode' => 3,
                'branch' => 4,
                'departement' => 5,    
                'position' => 6,
                'title' => 7,
                'employee_status' => 8,
                'contract_count' => 9,
                'join_date' => 10,
                'effective_date' => 11,
                'end_effective_date' => 12,
                'religion' => 13,
                'gender' => 14,
                'marital_status' => 15,
                'place_of_birth' => 16,
                'date_of_birth' => 17,
                'address' => 18,
                'phone' => 19,
                'office_email' => 20,
                'personal_email' => 21,
                'npwp' => 22,
                'bpjs_tk' => 23,
                'bpjs_health' => 24,
                'ktp_number' => 25,
            ]
        );

        $_SESSION['alert'] = [
            'type' => 'success',
            'msg'  => 'Data berhasil diimport'
        ];

    } catch (Exception $e) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'msg'  => $e->getMessage()
        ];
    }

     header("Location: /hris/employees/");
     exit;
}

if (isset($_POST['updateEmployee'])) {
    header('Content-Type: application/json; charset=utf-8');

    $id = $_POST['id'];
    $name = trim($_POST['name']);

    if (!$name) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE employees SET name=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Employee berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal update employee']);
    }

    $stmt->close();
}