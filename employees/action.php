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
                'location' => 7,
                'title' => 8,
                'employee_status' => 9,
                'contract_count' => 10,
                'join_date' => 11,
                'effective_date' => 12,
                'end_effective_date' => 13,
                'religion' => 14,
                'gender' => 15,
                'marital_status' => 16,
                'place_of_birth' => 17,
                'date_of_birth' => 18,
                'address' => 19,
                'phone' => 20,
                'office_email' => 21,
                'personal_email' => 22,
                'npwp' => 23,
                'bpjs_tk' => 24,
                'bpjs_health' => 25,
                'ktp_number' => 26,
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

