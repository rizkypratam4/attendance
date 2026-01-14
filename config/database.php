<?php
$conn = new mysqli("localhost", "root", "", "hris");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}