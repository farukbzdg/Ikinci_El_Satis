<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<div class="container" style="max-width:400px;">
    <h3 class="mb-4 text-center">Kayıt Ol</h3>

    <form action="/ikinci_el_satis/api/kullanici_kayit.php" method="POST">
        <input type="text" name="ad" class="form-control mb-3" placeholder="Ad" required>
        <input type="text" name="soyad" class="form-control mb-3" placeholder="Soyad" required>
        <input type="email" name="email" class="form-control mb-3" placeholder="E-posta" required>
        <input type="password" name="sifre" class="form-control mb-3" placeholder="Şifre" required>
        <button class="btn btn-success w-100">Kayıt Ol</button>
    </form>
</div>

</body>
</html>
