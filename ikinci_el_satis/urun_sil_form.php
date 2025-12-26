<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Sil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<h3>Ürün Sil</h3>

<form action="api/urun_sil.php" method="POST" onsubmit="return confirm('Bu ürünü silmek istediğine emin misin?');">
    <div class="mb-3">
        <label>Silinecek Ürün ID</label>
        <input type="number" name="id" class="form-control" placeholder="Ürün ID" required>
    </div>
    <button type="submit" class="btn btn-danger">Ürünü Sil</button>
    <a href="index.php" class="btn btn-secondary">Ana Menüye Dön</a>
</form>

</body>
</html>