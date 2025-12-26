<?php
require_once "config/db.php";

$stmt = $conn->query("SELECT kategori_id, kategori_adi FROM kategoriler ORDER BY kategori_adi ASC");
$kategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Kategori Sil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">
    <h2>Kategori Sil</h2>
    <form action="api/kategori_sil.php" method="POST" onsubmit="return confirm('Seçili kategoriyi silmek istediğinize emin misiniz?');">
        <div class="mb-3">
            <label for="kategori_id" class="form-label">Silinecek Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-select" required>
                <option value="">Kategori seçiniz</option>
                <?php foreach ($kategoriler as $kat): ?>
                    <option value="<?= $kat['kategori_id'] ?>"><?= htmlspecialchars($kat['kategori_adi']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Sil</button>
        <a href="index.php" class="btn btn-secondary">Geri</a>
    </form>
</body>
</html>