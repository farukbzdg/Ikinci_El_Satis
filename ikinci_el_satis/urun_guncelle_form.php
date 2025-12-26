<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/config/db.php";

/* GET ile ID al */
$urun_id = $_GET['id'] ?? null;
if (!$urun_id) {
    die("Ürün ID belirtilmedi (GET)");
}

/* Ürünü getir */
$stmt = $conn->prepare("SELECT * FROM urunler WHERE urun_id = :id");
$stmt->execute([':id' => $urun_id]);
$urun = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$urun_id) {
    header("Location: urun_guncelle_sec.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Güncelle</title>
</head>
<body>

<h2>Ürün Güncelle</h2>

<form action="api/urun_guncelle.php" method="POST" enctype="multipart/form-data">

    <!-- 🔴 ID BURADA -->
    <input type="hidden" name="urun_id" value="<?= $urun['urun_id'] ?>">

    <label>Ürün Adı</label><br>
    <input type="text" name="urun_adi" value="<?= htmlspecialchars($urun['urun_adi']) ?>"><br><br>

    <label>Fiyat</label><br>
    <input type="number" name="fiyat" value="<?= $urun['fiyat'] ?>"><br><br>

    <label>Açıklama</label><br>
    <textarea name="aciklama"><?= htmlspecialchars($urun['aciklama']) ?></textarea><br><br>

    <label>Yeni Fotoğraf (zorunlu değil)</label><br>
    <input type="file" name="fotograf"><br><br>

    <button type="submit">Güncelle</button>
</form>

</body>
</html>
