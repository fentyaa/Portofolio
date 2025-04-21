<?php
// Database connection
$host = 'localhost';
$dbname = 'portfolio_db';
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Ambil data dari form
$nama = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$pesan = filter_input(INPUT_POST, 'pesan', FILTER_SANITIZE_STRING);

// Validasi sederhana di sisi server
if (empty($nama) || empty($email) || empty($phone) || empty($pesan)) {
    die("Semua field wajib diisi!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email tidak valid!");
}

if (!preg_match('/^\d{10,15}$/', $phone)) {
    die("Nomor handphone tidak valid!");
}

if (strlen($nama) > 50 || strlen($email) > 100 || strlen($pesan) > 500) {
    die("Panjang input melebihi batas maksimum!");
}

// Simpan data ke database
try {
    $stmt = $pdo->prepare("INSERT INTO contacts (nama, email, phone, pesan) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $email, $phone, $pesan]);
    echo "Pesan berhasil dikirim!";
    header("Location: index.html#kontak");
    exit();
} catch (PDOException $e) {
    die("Gagal menyimpan data: " . $e->getMessage());
}
?>