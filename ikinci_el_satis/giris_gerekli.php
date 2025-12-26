<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Giriş Gerekli</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh">

<div class="card shadow p-4 text-center" style="max-width:420px">
    <h4 class="mb-3 text-danger">⚠️ Giriş Gerekli</h4>

    <p class="mb-4">
        Satın alma işlemi yapabilmek için<br>
        lütfen giriş yapınız.
    </p>

    <div class="d-flex gap-2 justify-content-center">
        <a href="giris.php" class="btn btn-primary">Giriş Yap</a>
        <a href="kayit.php" class="btn btn-outline-success">Kayıt Ol</a>
    </div>

    <a href="index.php" class="d-block mt-3 text-secondary">
        Ana sayfaya dön
    </a>
</div>

</body>
</html>
