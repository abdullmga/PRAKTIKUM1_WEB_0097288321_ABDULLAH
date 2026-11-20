<?php

session_start();
require 'config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$sukses = '';
$error = '';

try {
    if (isset($_POST['btn-daftar'])) {
        $nama_lengkap = $_POST['nama_lengkap'];
        $email = $_POST['email'];
        $no_hp = $_POST['no_hp'];
        $password = $_POST['password'];
        $ulangi_password = $_POST['ulangi_password'];

        if (empty($nama_lengkap) || empty($email) || empty($no_hp) || empty($password) || empty($ulangi_password)) {
            $error = "Semua field wajib diisi !";
        } elseif ($password != $ulangi_password) {
            $error = "Konfirmasi kata sandi tidak cocok !";
        } elseif (ctype_alpha($password) || ctype_digit($password)) {
            $error = "Kata sandi minimal 6 karakter dan wajib mengandung angka !";
        } else {
            $query = "INSERT INTO tb_pelanggan (nama_lengkap, email, no_hp, password) VALUES (:nama_lengkap, :email, :no_hp, :password)";

            $command = $koneksi->prepare($query);
            $command->execute([
                "nama_lengkap" => $nama_lengkap,
                "email" => $email,
                "no_hp" => $no_hp,
                "password" => $password
            ]);

            $sukses = "Pendaftaran berhasil, silakan login !";
        }
    }
} catch (PDOException $e) {
    if ($e->getCode() == 1062) {
        $error = "Email sudah digunakan, gunakan email lain !";
    } else {
        $error = "Terjadi kesalahan " . $e->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Member - Kopi Nusantara</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4efe6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 25px 20px;
        }

        .auth-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 480px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(78, 52, 46, 0.12);
            border: 1px solid #e0d5c1;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand-header h2 {
            color: #4e342e;
            font-size: 24px;
            margin-bottom: 6px;
        }

        .brand-header p {
            color: #795548;
            font-size: 14px;
        }

        .alert-box-sukses {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
            display: block;
        }
        .alert-box-error {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            background-color: #f5e8e8;
            color: #ff0000;
            border: 1px solid #e6c8c8;
            display: block;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #4e342e;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #d7ccc8;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: #6d4c41;
            box-shadow: 0 0 0 3px rgba(109, 76, 65, 0.15);
        }

        .form-row {
            display: flex;
            gap: 12px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #4e342e;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #3e2723;
        }

        .auth-footer {
            margin-top: 22px;
            text-align: center;
            font-size: 13px;
            color: #6d4c41;
        }

        .auth-footer a {
            color: #8d6e63;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <div class="brand-header">
            <h2>Kopi Nusantara</h2>
            <p>Formulir Pendaftaran Member Baru</p>
        </div>

        <?php if($sukses) :?>
        <div class="alert-box-sukses">
            <span><?= $sukses ?></span>
        </div>
        <?php endif; ?>

        <?php if($error) :?>
        <div class="alert-box-error">
            <span><?= $error ?></span>
        </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp / HP</label>
                <input type="tel" name="no_hp" class="form-control" placeholder="081234567890" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group">
                    <label>Ulangi Sandi</label>
                    <input type="password" name="ulangi_password" class="form-control" placeholder="Ketik ulang kata sandi" required>
                </div>
            </div>

            <button type="submit" class="btn-submit" name="btn-daftar">Daftarkan Akun Member</button>
        </form>

        <div class="auth-footer">
            Sudah memiliki akun member? <a href="login.php">Masuk di sini</a>
        </div>
    </div>

</body>

</html>