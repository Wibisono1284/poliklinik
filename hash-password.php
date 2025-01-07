<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'poliklinik_db');

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Username dan password yang ingin dimasukkan
$username = 'drvicky';
$password = 'drvicky';  // Password asli yang ingin di-hash

// Meng-hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Menyisipkan data username dan hashed password ke dalam tabel login_dokter
$query = $conn->prepare("INSERT INTO login_dokter (username, password) VALUES (?, ?)");
$query->bind_param("ss", $username, $hashed_password);

// Eksekusi query
if ($query->execute()) {
    echo "Password berhasil di-hash dan dimasukkan ke dalam database!";
} else {
    echo "Gagal memasukkan data: " . $query->error;
}

// Menutup query dan koneksi
$query->close();
$conn->close();
?>
