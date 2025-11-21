<?php
session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = isset($_POST['username']) ? pg_escape_string($conn, $_POST['username']) : '';
    $password = isset($_POST['password']) ? pg_escape_string($conn, $_POST['password']) : '';

    $query = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $result = pg_query($conn, $query);

    if ($result && pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);

        if ($password === $user['password']) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['nama_admin'] = $user['nama_lengkap'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }

    } else {
        $error = "Username tidak ditemukan!";
    }

}

?>



<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login Admin | Lab AI</title>
  <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #ffffff;
      background-image: url('../assets/img/login.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .img-container {
      position: absolute;
      top: 20px;
      left: 20px;
    }

    .img-container img {
      width: 140px; 
      height: auto;
    }


    .card {
      background: rgba(255, 255, 255, 0.25);
      border-radius: 16px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      border: 1px solid rgba(255, 255, 255, 0.81);
    }

    .text-danger {
      color: #bb1212ff !important;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.25);
      border: none;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      color: #000;
    }

    .card-body {
      padding: 2rem !important;
    }


    .login-header {
      text-align: center;
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="img-container">
    <a href="../index.php">
        <img src="../assets/img/logoclear.png" alt="Logo">
        </a>
    </div>

  <div class="card w-25">
    <div class="card-body">
    
      <div class="login-header fs-3">Login</div>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label text-white fw-bold">Username/Email</label>
          <div class="col-12">
            <input type="text" name="username" class="form-control" placeholder="Masukkan username atau email" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label text-white fw-bold">Password</label>
          <div class="col-12">
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
          </div>
        </div>

        <?php if ($error): ?>
        <div class="text-danger fw-bolder"><?= $error ?></div>
      <?php endif; ?>

        <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
      </form>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>