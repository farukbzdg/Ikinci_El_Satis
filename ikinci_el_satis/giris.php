<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Giriş Yap</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Giriş Yap</h4>
                </div>
                <div class="card-body">
                    <form action="/ikinci_el_satis/api/kullanici_giris.php" method="POST">
                        <input type="email" name="email" class="form-control mb-3" placeholder="E-posta" required>
                        <input type="password" name="sifre" class="form-control mb-3" placeholder="Şifre" required>
                        <button class="btn btn-primary w-100">Giriş Yap</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="/ikinci_el_satis/index.php">Ana sayfa</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
