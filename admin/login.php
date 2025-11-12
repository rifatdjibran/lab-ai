<?php
session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = pg_escape_string($conn, $_POST['username']);
    $password = pg_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $result = pg_query($conn, $query);
    $user = pg_fetch_assoc($result);

    if ($user && $password === $user['password']) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['nama_admin'] = $user['nama_lengkap'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin | Lab AI</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
          <h5>Login Admin</h5>
        </div>
        <div class="card-body">
          <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
