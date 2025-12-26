<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Kategori Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
    <h2>Kategori Ekle</h2>
    <form action="api/kategori_ekle.php" method="POST">
        <div class="mb-3">
            <label for="kategori_adi" class="form-label">Kategori Adı</label>
            <input type="text" id="kategori_adi" name="kategori_adi" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Ekle</button>
        <a href="index.php" class="btn btn-secondary">Geri</a>
    </form>
</body>
</html>