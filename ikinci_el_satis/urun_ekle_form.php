<?php
require_once "config/db.php";

$kategori_stmt = $conn->query("SELECT kategori_id, kategori_adi FROM kategoriler ORDER BY kategori_adi ASC");
$kategoriler = $kategori_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body { background: #f7f7f7; }
        .form-kutu {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="form-kutu">
    <h3 class="text-center mb-4">Yeni Ürün Ekle</h3>

    <form action="api/urun_ekle.php" method="POST" enctype="multipart/form-data">

        <!-- Kullanıcı Adı -->
        <div class="mb-3">
            <label class="form-label">Ad</label>
            <input type="text" name="ad" class="form-control" required>
        </div>

        <!-- Kullanıcı Soyadı -->
        <div class="mb-3">
            <label class="form-label">Soyad</label>
            <input type="text" name="soyad" class="form-control" required>
        </div>

        <!-- Kategori -->
        <div class="mb-3">
            <label class="form-label">Kategori Seç</label>
            <select name="kategori_id" class="form-select" required>
                <option value="">Kategori seçin...</option>
                <?php foreach ($kategoriler as $kat): ?>
                    <option value="<?= $kat['kategori_id'] ?>">
                        <?= htmlspecialchars($kat['kategori_adi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ürün Adı</label>
            <input type="text" name="urun_adi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fiyat (TL)</label>
            <input type="number" name="fiyat" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" rows="3" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ürün Fotoğrafı</label>
            <input type="file" name="fotograf" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Ürünü Ekle</button>

    </form>

</div>

</body>
</html>