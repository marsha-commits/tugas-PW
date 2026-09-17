<?php
session_start();

if (!isset($_SESSION['kas'])) {
    $_SESSION['kas'] = [];
}

// Tambah data
if (isset($_POST['tambah'])) {
    $_SESSION['kas'][] = [
        'jenis' => $_POST['jenis'],
        'keterangan' => $_POST['keterangan'],
        'jumlah' => (int) $_POST['jumlah']
    ];

    header("Location: buku-kas.php");
    exit;
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    if (isset($_SESSION['kas'][$id])) {
        unset($_SESSION['kas'][$id]);
        $_SESSION['kas'] = array_values($_SESSION['kas']);
    }

    header("Location: buku-kas.php");
    exit;
}

// Edit data
if (isset($_POST['edit'])) {
    $id = (int) $_POST['id'];

    if (isset($_SESSION['kas'][$id])) {
        $_SESSION['kas'][$id] = [
            'jenis' => $_POST['jenis'],
            'keterangan' => $_POST['keterangan'],
            'jumlah' => (int) $_POST['jumlah']
        ];
    }

    header("Location: buku-kas.php");
    exit;
}

// Data yang sedang diedit
$dataEdit = null;

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    if (isset($_SESSION['kas'][$id])) {
        $dataEdit = $_SESSION['kas'][$id];
    }
}

// Hitung saldo
$saldo = 0;
$totalMasuk = 0;
$totalKeluar = 0;

foreach ($_SESSION['kas'] as $data) {
    if ($data['jenis'] == 'Masuk') {
        $totalMasuk += $data['jumlah'];
        $saldo += $data['jumlah'];
    } else {
        $totalKeluar += $data['jumlah'];
        $saldo -= $data['jumlah'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Kas - Tugas PW</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
        }

        form {
            margin-bottom: 25px;
        }

        input, select, button {
            padding: 9px;
            margin: 5px 0;
        }

        input, select {
            width: 100%;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
            border: none;
            padding: 10px 18px;
        }

        .tambah {
            background: #2196f3;
            color: white;
        }

        .edit {
            background: #ff9800;
            color: white;
            text-decoration: none;
            padding: 7px 10px;
        }

        .hapus {
            background: #f44336;
            color: white;
            text-decoration: none;
            padding: 7px 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #333;
            color: white;
        }

        .masuk {
            color: green;
            font-weight: bold;
        }

        .keluar {
            color: red;
            font-weight: bold;
        }

        .saldo {
            background: #eee;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .ringkasan {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .box {
            width: 30%;
            padding: 15px;
            text-align: center;
            background: #eee;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>BUKU KAS</h1>
    <p style="text-align:center;">Tugas Pengembangan Web (PW)</p>

    <?php if ($dataEdit): ?>

        <h3>Edit Data Kas</h3>

        <form method="post">
            <input type="hidden" name="id"
                   value="<?= $_GET['edit'] ?>">

            <label>Jenis Kas</label>
            <select name="jenis">
                <option value="Masuk"
                    <?= $dataEdit['jenis'] == 'Masuk' ? 'selected' : '' ?>>
                    Kas Masuk
                </option>

                <option value="Keluar"
                    <?= $dataEdit['jenis'] == 'Keluar' ? 'selected' : '' ?>>
                    Kas Keluar
                </option>
            </select>

            <label>Keterangan</label>
            <input type="text"
                   name="keterangan"
                   value="<?= htmlspecialchars($dataEdit['keterangan']) ?>"
                   required>

            <label>Jumlah</label>
            <input type="number"
                   name="jumlah"
                   value="<?= $dataEdit['jumlah'] ?>"
                   required>

            <button class="tambah" type="submit" name="edit">
                Simpan Perubahan
            </button>

            <a href="buku-kas.php">Batal</a>
        </form>

    <?php else: ?>

        <h3>Tambah Data Kas</h3>

        <form method="post">

            <label>Jenis Kas</label>
            <select name="jenis">
                <option value="Masuk">Kas Masuk</option>
                <option value="Keluar">Kas Keluar</option>
            </select>

            <label>Keterangan</label>
            <input type="text"
                   name="keterangan"
                   placeholder="Contoh: Uang iuran kelas"
                   required>

            <label>Jumlah</label>
            <input type="number"
                   name="jumlah"
                   placeholder="Masukkan jumlah"
                   required>

            <button class="tambah" type="submit" name="tambah">
                Tambah Data
            </button>

        </form>

    <?php endif; ?>

    <div class="ringkasan">

        <div class="box">
            <b>Kas Masuk</b>
            <br>
            Rp <?= number_format($totalMasuk, 0, ',', '.') ?>
        </div>

        <div class="box">
            <b>Kas Keluar</b>
            <br>
            Rp <?= number_format($totalKeluar, 0, ',', '.') ?>
        </div>

        <div class="box">
            <b>Saldo</b>
            <br>
            Rp <?= number_format($saldo, 0, ',', '.') ?>
        </div>

    </div>

    <h3>Data Buku Kas</h3>

    <table>
        <tr>
            <th>No</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Jumlah</th>
            <th>Aksi</th>
        </tr>

        <?php if (empty($_SESSION['kas'])): ?>

            <tr>
                <td colspan="5">
                    Belum ada data kas.
                </td>
            </tr>

        <?php else: ?>

            <?php foreach ($_SESSION['kas'] as $no => $data): ?>

                <tr>
                    <td><?= $no + 1 ?></td>

                    <td class="<?= strtolower($data['jenis']) ?>">
                        <?= $data['jenis'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['keterangan']) ?>
                    </td>

                    <td>
                        Rp <?= number_format(
                            $data['jumlah'],
                            0,
                            ',',
                            '.'
                        ) ?>
                    </td>

                    <td>
                        <a class="edit"
                           href="?edit=<?= $no ?>">
                            Edit
                        </a>

                        <a class="hapus"
                           href="?hapus=<?= $no ?>"
                           onclick="return confirm('Yakin ingin menghapus data ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </table>

    <div class="saldo">
        Saldo Akhir:
        Rp <?= number_format($saldo, 0, ',', '.') ?>
    </div>

</div>

</body>
</html>
