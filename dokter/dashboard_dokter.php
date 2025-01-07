<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['dokter']['id'])) {
    header("Location: login_dokter.php");
    exit();
}

require_once '../admin/koneksi.php';

$id_dokter = $_SESSION['dokter']['id'];
$sql = "SELECT jp.*, d.nama AS nama_dokter, p.nama_poli 
        FROM jadwal_periksa jp
        JOIN dokter d ON jp.id_dokter = d.id
        JOIN poli p ON d.id_poli = p.id
        WHERE jp.id_dokter = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_dokter);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #1a3e6d;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }

        .sidebar img {
            margin-bottom: 20px;
        }

        .sidebar h2 {
            margin: 0 0 20px 0;
            font-size: 20px;
            text-align: center;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            margin-bottom: 10px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #2a5d99;
            color: #e0e0e0;
        }

        .sidebar a.active {
            background-color: #144266;
            color: #ffffff;
            font-weight: bold;
            border-left: 5px solid #ffffff;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
            width: calc(100% - 240px);
            background-color: #f4f4f4;
            min-height: 100vh;
        }

        .btn {
            padding: 10px 15px;
            background-color: #1a3e6d;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2a5d99;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="../assets/img/hospital.svg" alt="Hospital Logo" width="50px">
        <h2>Panel Dokter</h2>
        <a href="dashboard_dokter.php" class="active">Jadwal Periksa</a>
        <a href="daftar_periksa_pasien.php">Memeriksa Pasien</a>
        <a href="riwayat_pasien.php">Riwayat Pasien</a>
        <a href="profil_dokter.php">Profil</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Jadwal Periksa</h1>
        <a href="tambah_jadwal_periksa.php" class="btn">Tambah Jadwal Periksa</a>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Poli</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['hari']); ?></td>
                            <td><?= htmlspecialchars($row['jam_mulai']); ?></td>
                            <td><?= htmlspecialchars($row['jam_selesai']); ?></td>
                            <td><?= htmlspecialchars($row['nama_poli']); ?></td>
                            <td><?= $row['status'] ? 'Aktif' : 'Tidak Aktif'; ?></td>
                            <td>
                                <a href="edit_jadwal_periksa.php?id=<?= $row['id']; ?>" class="btn">Ubah</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Tidak ada jadwal periksa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
