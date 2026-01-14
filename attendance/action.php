<?php
session_start();
require __DIR__ . "/../config/database.php";
require __DIR__ . "../../function.php";

if (isset($_POST['import'])) {
    try {
        importExcel(
            $conn,
            'attendances',
            [
                'employee_id'   => 1,
                'attendance_date' => 2,
                'attendance_time' => 3,
                'attendance_type' => 4,
            ]
        );

        $_SESSION['alert'] = [
            'type' => 'success',
            'msg'  => 'Data berhasil diimport'
        ];

    } catch (Exception $e) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'msg'  => $e->getMessage()
        ];
    }

     header("Location: /hris/attendance/");
     exit;
}
