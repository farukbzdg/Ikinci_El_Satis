<?php
// DB bağlantısını dahil et
require_once __DIR__ . "/../config/db.php"; // api klasöründen bir üst dizine çıkıp config/db.php

$mesaj = '';

// Silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['kategori_id'] ?? null;
    if ($id) {
        // Kategoriye bağlı ürün var mı kontrol et
        $kontrol = $conn->prepare("SELECT COUNT(*) FROM urunler WHERE kategori_id = :id");
        $kontrol->execute([':id' => $id]);
        $urunSayisi = $kontrol->fetchColumn();

        if ($urunSayisi > 0) {
            $mesaj = "Bu kategoride $urunSayisi ürün bulundu. Önce ürünleri silmelisiniz!";
        } else {
            $stmt = $conn->prepare("DELETE FROM kategoriler WHERE kategori_id = :id");
            $stmt->execute([':id' => $id]);
            $mesaj = "Kategori başarıyla silindi!";
        }
    } else {
        $mesaj = "Kategori ID gönderilmedi!";
    }
}

$stmt = $conn->query("SELECT kategori_id, kategori_adi FROM kategoriler ORDER BY kategori_adi ASC");
$kategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kategori Sil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Kategori Sil</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return confirm('Seçili kategoriyi silmek istediğinize emin misiniz?');">
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
        <a href="../index.php" class="btn btn-secondary">Geri</a>
    </form>
</body>
</html>